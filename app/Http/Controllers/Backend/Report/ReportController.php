<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{

    public function saleReport(Request $request)
    {

        abort_if(!auth()->user()->can(abilities: 'reports_sales'), 403);
        // Get user input or set default values
        $start_date_input = $request->input('start_date', Carbon::today()->subDays(29)->format('Y-m-d'));
        $end_date_input = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // Parse and set start date
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date_input) ?: Carbon::today()->subDays(29)->startOfDay();
        $start_date = $start_date->startOfDay();

        // Parse and set end date
        $end_date = Carbon::createFromFormat('Y-m-d', $end_date_input) ?: Carbon::today()->endOfDay();
        $end_date = $end_date->endOfDay();
        // Paginated orders for display
        $orders = Order::whereBetween('created_at', [$start_date, $end_date])
            ->with('customer')
            ->paginate(100);

        // Totals via SQL aggregate (single query, no PHP collection looping)
        $totals = Order::whereBetween('created_at', [$start_date, $end_date])
            ->selectRaw('SUM(sub_total) as sub_total, SUM(discount) as discount, SUM(paid) as paid, SUM(due) as due, SUM(total) as total')
            ->first();

        // Calculate totals
        $data = [
            'orders'     => $orders,
            'sub_total'  => $totals->sub_total ?? 0,
            'discount'   => $totals->discount ?? 0,
            'paid'       => $totals->paid ?? 0,
            'due'        => $totals->due ?? 0,
            'total'      => $totals->total ?? 0,
            'start_date' => $start_date->format('M d, Y'),
            'end_date'   => $end_date->format('M d, Y'),
        ];

        return view('backend.reports.sale-report', $data);
    }
    public function saleSummery(Request $request)
    {

        abort_if(!auth()->user()->can('reports_summary'), 403);
        // Get user input or set default values
        $start_date_input = $request->input('start_date', Carbon::today()->subDays(29)->format('Y-m-d'));
        $end_date_input = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // Parse and set start date
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date_input) ?: Carbon::today()->subDays(29)->startOfDay();
        $start_date = $start_date->startOfDay();

        // Parse and set end date
        $end_date = Carbon::createFromFormat('Y-m-d', $end_date_input) ?: Carbon::today()->endOfDay();
        $end_date = $end_date->endOfDay();
        $orderTotals = Order::whereBetween('created_at', [$start_date, $end_date])
            ->selectRaw('SUM(sub_total) as sub_total, SUM(discount) as discount, SUM(paid) as paid, SUM(due) as due, SUM(total) as total')
            ->first();

        // Calculate totals
        $data = [
            'sub_total' => $orderTotals->sub_total ?? 0,
            'discount' => $orderTotals->discount ?? 0,
            'paid' => $orderTotals->paid ?? 0,
            'due' => $orderTotals->due ?? 0,
            'total' => $orderTotals->total ?? 0,
            'start_date' => $start_date->format('M d, Y'),
            'end_date' => $end_date->format('M d, Y'),
        ];

        return view('backend.reports.sale-summery', $data);
    }
    public function platformSalesReport(Request $request)
    {
        abort_if(!auth()->user()->can('reports_sales'), 403);

        $start_date_input = $request->input('start_date', Carbon::today()->subDays(29)->format('Y-m-d'));
        $end_date_input = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        $start_date = (Carbon::createFromFormat('Y-m-d', $start_date_input) ?: Carbon::today()->subDays(29))->startOfDay();
        $end_date = (Carbon::createFromFormat('Y-m-d', $end_date_input) ?: Carbon::today())->endOfDay();

        // Aggregate per channel using single SQL query
        $rows = Order::leftJoin('sales_channels', 'orders.sales_channel_id', '=', 'sales_channels.id')
            ->whereBetween('orders.created_at', [$start_date, $end_date])
            ->whereNull('orders.deleted_at')
            ->selectRaw('
                COALESCE(sales_channels.id, 0) as channel_id,
                COALESCE(sales_channels.name, \'หน้าร้าน\') as channel_name,
                COALESCE(sales_channels.color, \'#28a745\') as channel_color,
                COALESCE(sales_channels.icon, \'fas fa-store\') as channel_icon,
                COALESCE(sales_channels.commission_percent, 0) as commission_percent,
                COUNT(orders.id) as order_count,
                SUM(orders.sub_total) as gross_sales,
                SUM(orders.discount) as total_discount,
                SUM(orders.total) as net_sales,
                SUM(orders.platform_fee) as total_platform_fee
            ')
            ->groupBy('sales_channels.id', 'sales_channels.name', 'sales_channels.color', 'sales_channels.icon', 'sales_channels.commission_percent')
            ->orderByDesc('net_sales')
            ->get();

        $grandTotals = [
            'order_count' => (int) $rows->sum('order_count'),
            'gross_sales' => (float) $rows->sum('gross_sales'),
            'total_discount' => (float) $rows->sum('total_discount'),
            'net_sales' => (float) $rows->sum('net_sales'),
            'total_platform_fee' => (float) $rows->sum('total_platform_fee'),
        ];
        $grandTotals['net_after_fee'] = $grandTotals['net_sales'] - $grandTotals['total_platform_fee'];

        return view('backend.reports.platform-sales', [
            'rows' => $rows,
            'grandTotals' => $grandTotals,
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
        ]);
    }

    function inventoryReport(Request $request)
    {

        abort_if(!auth()->user()->can('reports_inventory'), 403);
        if ($request->ajax()) {
            $products = Product::with('unit')->latest()->active();
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('sku', fn($data) => $data->sku)
                ->addColumn(
                    'price',
                    fn($data) => $data->discounted_price .
                        ($data->price > $data->discounted_price
                            ? '<br><del>' . $data->price . '</del>'
                            : '')
                )
                ->addColumn('quantity', fn($data) => $data->quantity . ' ' . optional($data->unit)->short_name)
                ->rawColumns(['name', 'sku', 'price', 'quantity', 'status'])
                ->toJson();
        }
        return view('backend.reports.inventory');
    }
}
