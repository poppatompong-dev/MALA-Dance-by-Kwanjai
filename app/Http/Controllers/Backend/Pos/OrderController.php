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
            $orders = Order::with('customer')->get();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('saleId', fn($data) => "#" . $data->id)
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
                ->rawColumns(['saleId', 'customer', 'item', 'sub_total', 'discount', 'total', 'paid', 'due', 'status', 'action'])
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
            'customer_id' => [
                'required',
                'exists:customers,id',
                'integer', // Ensure customer_id is an integer
            ],
            'order_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'paid' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'applied_rewards' => 'nullable|array',
            'applied_rewards.*' => 'integer|exists:reward_rules,id',
            'order_type' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'customer_id.required' => 'กรุณาเลือกลูกค้า',
            'customer_id.exists' => 'ไม่พบลูกค้าที่เลือก',
            'order_discount.numeric' => 'ส่วนลดต้องเป็นตัวเลข',
            'paid.numeric' => 'ยอดรับเงินต้องเป็นตัวเลข',
        ]);
        return DB::transaction(function () use ($request) {
            $carts = PosCart::with('product')->where('user_id', auth()->id())->get();
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user()->id,
                'order_type' => $request->order_type ?? 'dine_in',
                'notes' => $request->notes,
            ]);
            $totalAmountOrder = 0;
            $orderDiscount = $request->order_discount;
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
                );
            }

            // --- REWARD LOGIC ---
            $appliedRewards = $request->applied_rewards ?? [];
            $rewardDiscount = 0;
            $pointsEarned = floor($totalAmountOrder / 10); // Base rate: 1 pt per 10 THB
            $pointsRedeemed = 0;
            $customer = \App\Models\Customer::find($request->customer_id);

            foreach ($appliedRewards as $ruleId) {
                $rule = \App\Models\RewardRule::find($ruleId);
                if ($rule) {
                    $result = app(\App\Services\RewardService::class)->calculateReward($rule, $customer, $totalAmountOrder);
                    $rewardDiscount += $result['discount'];
                    
                    if ($result['points_change'] > 0) {
                        $pointsEarned += $result['points_change'];
                    } else {
                        $pointsRedeemed += abs($result['points_change']);
                    }

                    \App\Models\RewardUsage::create([
                        'reward_rule_id' => $rule->id,
                        'customer_id' => $customer->id,
                        'order_id' => $order->id,
                        'discount_applied' => $result['discount'],
                        'points_changed' => $result['points_change']
                    ]);
                    
                    $rule->increment('usage_count');
                }
            }

            if ($customer) {
                $customer->points += $pointsEarned - $pointsRedeemed;
                $customer->total_spent += $totalAmountOrder;
                $customer->visit_count += 1;
                $customer->last_visit_at = now();
                $customer->save();
            }
            
            $orderDiscount = ($request->order_discount ?? 0) + $rewardDiscount;
            // --------------------

            $total = $totalAmountOrder - $orderDiscount;
            $due = $total - $request->paid;
            $order->sub_total = $totalAmountOrder;
            $order->discount = $orderDiscount;
            $order->paid = $request->paid;
            $order->total = round((float)$total, 2);
            $order->due = round((float)$due, 2);
            $order->status = round((float)$due, 2) <= 0;
            $order->save();
            //create order transaction
            if ($request->paid > 0) {
                $orderTransaction = $order->transactions()->create([
                    'amount' => $request->paid,
                    'customer_id' => $order->customer_id,
                    'user_id' => auth()->id(),
                    'paid_by' => 'cash',
                ]);
            }

            $carts = PosCart::where('user_id', auth()->id())->delete();
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
        $rewardUsages = \App\Models\RewardUsage::with('rewardRule')->where('order_id', $id)->get();
        return view('backend.orders.pos-invoice', compact('order', 'maxWidth', 'rewardUsages'));
    }

    public function voidOrder($id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::with('products.product')->findOrFail($id);
            
            // Return stock
            foreach ($order->products as $item) {
                app(\App\Services\InventoryService::class)->adjustStock(
                    $item->product,
                    $item->quantity, // Add back
                    'void',
                    Order::class,
                    $order->id,
                    'Order Voided',
                    auth()->id()
                );
            }

            // Reverse Rewards & Points
            $usages = \App\Models\RewardUsage::where('order_id', $order->id)->get();
            $customer = \App\Models\Customer::find($order->customer_id);
            
            $pointsToReverse = floor($order->sub_total / 10); // Base points earned

            foreach ($usages as $usage) {
                if ($usage->points_changed > 0) {
                    $pointsToReverse += $usage->points_changed;
                } else {
                    // Redeemed points, so give them back
                    $pointsToReverse += $usage->points_changed; // points_changed is negative, so adding it actually subtracts from the reversal amount? Wait.
                    // If they redeemed 100 points, points_changed is -100.
                    // When they void, we should give back 100.
                    // Customer points = points - pointsToReverse + abs(redeemed).
                    // Actually, let's process it directly.
                }

                $rule = \App\Models\RewardRule::find($usage->reward_rule_id);
                if ($rule) {
                    $rule->decrement('usage_count');
                }
                $usage->delete();
            }

            if ($customer) {
                // Re-calculate the exact points they got from this order
                // Earned: Base + Bonus
                // Redeemed: Abs(Negative)
                $earnedInOrder = floor($order->sub_total / 10);
                $redeemedInOrder = 0;
                foreach ($usages as $u) {
                    if ($u->points_changed > 0) $earnedInOrder += $u->points_changed;
                    if ($u->points_changed < 0) $redeemedInOrder += abs($u->points_changed);
                }
                
                // Reverse it
                $customer->points -= $earnedInOrder;
                $customer->points += $redeemedInOrder;
                $customer->total_spent -= $order->sub_total;
                $customer->visit_count = max(0, $customer->visit_count - 1);
                $customer->save();
            }

            // Audit log
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
