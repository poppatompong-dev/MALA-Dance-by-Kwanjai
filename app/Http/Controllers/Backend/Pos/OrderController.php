<?php

namespace App\Http\Controllers\Backend\Pos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\PosCart;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with(['customer', 'salesChannel'])
                ->withSum('products', 'quantity')
                ->select('orders.*')
                ->latest();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('saleId', fn($data) => "#" . $data->id)
                ->addColumn('channel', function ($data) {
                    if (!$data->salesChannel) return '<span class="text-muted">-</span>';
                    return '<span class="badge" style="background-color:'.e($data->salesChannel->color).';color:#fff;"><i class="'.e($data->salesChannel->icon ?? 'fas fa-tag').'"></i> '.e($data->salesChannel->name).'</span>'
                        . ($data->platform_order_ref ? '<br><small class="text-muted">'.e($data->platform_order_ref).'</small>' : '');
                })
                ->addColumn('customer', fn($data) => $data->customer->name ?? '-')
                ->addColumn('item', fn($data) => $data->total_item)
                ->addColumn('sub_total', fn($data) => number_format($data->sub_total, 2, '.', ','))
                ->addColumn('discount', fn($data) => number_format($data->discount, 2, '.', ','))
                ->addColumn('total', fn($data) => number_format($data->total, 2, '.', ','))
                ->addColumn('paid', fn($data) => number_format($data->paid, 2, '.', ','))
                ->addColumn('due', fn($data) => number_format($data->due, 2, '.', ','))
                ->addColumn('status', fn($data) => $data->status
                    ? '<span class="badge bg-primary">ชำระแล้ว</span>'
                    : '<span class="badge bg-danger">ค้างชำระ</span>')
                ->addColumn('action', function ($data) {
                    $buttons = '';

                    $buttons .= '<a class="btn btn-success btn-sm" href="' . route('backend.admin.orders.invoice', $data->id) . '"><i class="fas fa-file-invoice"></i> ใบแจ้งหนี้</a>';

                    $buttons .= '<a class="btn btn-secondary btn-sm" href="' . route('backend.admin.orders.pos-invoice', $data->id) . '"><i class="fas fa-file-invoice"></i> ใบเสร็จ POS</a>';
                    if (!$data->status) {
                        $buttons .= '<a class="btn btn-warning btn-sm" href="' . route('backend.admin.due.collection', $data->id) . '"><i class="fas fa-receipt"></i> รับชำระค้าง</a>';
                    }
                    $buttons .= '<a class="btn btn-primary btn-sm" href="' . route('backend.admin.orders.transactions', $data->id) . '"><i class="fas fa-exchange-alt"></i> ธุรกรรม</a>';
                    
                    $buttons .= '
                        <form action="'.route('backend.admin.orders.void', $data->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'คุณแน่ใจหรือไม่ว่าต้องการยกเลิกออเดอร์นี้และคืนสต็อก?\');">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-danger btn-sm ml-1"><i class="fas fa-ban"></i> ยกเลิก (Void)</button>
                        </form>
                    ';
                    return $buttons;
                })
                ->rawColumns(['saleId', 'channel', 'customer', 'item', 'sub_total', 'discount', 'total', 'paid', 'due', 'status', 'action'])
                ->toJson();
        }
        return view('backend.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id', 'integer'],
            'order_discount' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['nullable', 'numeric', 'min:0'],
            'order_type' => 'nullable|string',
            'notes' => 'nullable|string',
            'sales_channel_id' => ['nullable', 'integer', 'exists:sales_channels,id'],
            'platform_order_ref' => ['nullable', 'string', 'max:100'],
        ], [
            'customer_id.required' => 'กรุณาเลือกลูกค้า',
            'customer_id.exists' => 'ไม่พบลูกค้าที่เลือก',
            'order_discount.numeric' => 'ส่วนลดต้องเป็นตัวเลข',
            'paid.numeric' => 'ยอดรับเงินต้องเป็นตัวเลข',
            'sales_channel_id.exists' => 'ช่องทางการขายไม่ถูกต้อง',
        ]);
        return DB::transaction(function () use ($request) {
            $carts = PosCart::with('product')->where('user_id', auth()->id())->get();
            $channel = $request->sales_channel_id
                ? \App\Models\SalesChannel::find($request->sales_channel_id)
                : \App\Models\SalesChannel::where('slug', 'walk_in')->first();

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user()->id,
                'order_type' => $request->order_type ?? 'dine_in',
                'notes' => $request->notes,
                'sales_channel_id' => $channel?->id,
                'platform_order_ref' => $request->platform_order_ref,
            ]);
            $totalAmountOrder = 0;
            foreach ($carts as $cart) {
                $mainTotal = $cart->product->price * $cart->quantity;
                $totalAfterDiscount = $cart->product->discounted_price * $cart->quantity;
                $discount = $mainTotal - $totalAfterDiscount;
                $totalAmountOrder += $totalAfterDiscount;
                $order->products()->create([
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'purchase_price' => $cart->product->purchase_price,
                    'sub_total' => $mainTotal,
                    'discount' => $discount,
                    'total' => $totalAfterDiscount,
                    'product_id' => $cart->product->id,
                    'spice_level' => $cart->spice_level,
                    'toppings' => $cart->toppings,
                ]);
                app(\App\Services\InventoryService::class)->adjustStock(
                    $cart->product,
                    -$cart->quantity,
                    'sale',
                    Order::class,
                    $order->id,
                    'Order Checkout POS',
                    auth()->id()
                );
            }

            $orderDiscount = (float) ($request->order_discount ?? 0);
            $total = $totalAmountOrder - $orderDiscount;
            $due = $total - (float) $request->paid;
            $platformFee = $channel ? $channel->calculateFee($total) : 0;

            $order->sub_total = $totalAmountOrder;
            $order->discount = $orderDiscount;
            $order->paid = $request->paid;
            $order->total = round((float)$total, 2);
            $order->due = round((float)$due, 2);
            $order->status = round((float)$due, 2) <= 0;
            $order->platform_fee = $platformFee;
            $order->save();

            if ($request->paid > 0) {
                $order->transactions()->create([
                    'amount' => $request->paid,
                    'customer_id' => $order->customer_id,
                    'user_id' => auth()->id(),
                    'paid_by' => 'cash',
                ]);
            }

            PosCart::where('user_id', auth()->id())->delete();
            return response()->json(['message' => 'บันทึกการขายเรียบร้อยแล้ว', 'order' => $order], 200);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function invoice($id)
    {
        $order = Order::with(['customer', 'products.product'])->findOrFail($id);
        return view('backend.orders.print-invoice', compact('order'));
    }
    public function collection(Request $request, $id)
    {

        $order = Order::findOrFail($id);
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);


            return DB::transaction(function () use ($data, $order) {
                $due = $order->due - $data['amount'];
                $paid = $order->paid + $data['amount'];
                $order->due = round((float)$due, 2);
                $order->paid = round((float)$paid, 2);
                $order->status = round((float)$due, 2) <= 0;
                $order->save();
                $collection_amount = $data['amount'];
                //create order transaction

                $orderTransaction = $order->transactions()->create([
                    'amount' => $data['amount'],
                    'customer_id' => $order->customer_id,
                    'user_id' => auth()->id(),
                    'paid_by' => 'cash',
                ]);
                return to_route('backend.admin.collectionInvoice', $orderTransaction->id);
            });
        }
        return view('backend.orders.collection.create', compact('order'));
    }

    //collection invoice by order_transaction id
    public function collectionInvoice($id)
    {
        $transaction = OrderTransaction::findOrFail($id);
        $collection_amount = $transaction->amount;
        $order = $transaction->order;
        return view('backend.orders.collection.invoice', compact('order', 'collection_amount', 'transaction'));
    }
    //transactions by order id
    public function transactions($id)
    {
        $order = Order::with('transactions')->findOrFail($id);
        return view('backend.orders.collection.index', compact('order'));
    }

    public function posInvoice($id)
    {
        $order = Order::with(['customer', 'products.product'])->findOrFail($id);
        $maxWidth = readConfig('receiptMaxwidth')??'300px';
        return view('backend.orders.pos-invoice', compact('order', 'maxWidth'));
    }

    public function voidOrder($id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::with('products.product')->findOrFail($id);

            foreach ($order->products as $item) {
                app(\App\Services\InventoryService::class)->adjustStock(
                    $item->product,
                    $item->quantity,
                    'void',
                    Order::class,
                    $order->id,
                    'Order Voided',
                    auth()->id()
                );
            }

            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'void_order',
                'description' => "Voided order #{$order->id} (Total: {$order->total})",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'target_type' => Order::class,
                'target_id' => $order->id,
            ]);

            $order->delete();
            return back()->with('success', 'ยกเลิกออเดอร์เรียบร้อยแล้ว สต็อกถูกคืนเข้าสู่ระบบ');
        });
    }
}
