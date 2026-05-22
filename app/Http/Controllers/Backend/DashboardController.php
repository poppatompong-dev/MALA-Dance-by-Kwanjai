<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Cache heavy aggregate queries for 5 minutes (300 seconds)
        $orderTotals = Cache::remember('dashboard_order_totals', 300, function () {
            return Order::selectRaw('
                SUM(sub_total) as total_sub_total,
                SUM(discount) as total_discount,
                SUM(total) as total_amount,
                SUM(paid) as total_paid,
                SUM(due) as total_due,
                COUNT(id) as total_orders
            ')->first();
        });

        $data = [
            'sub_total'        => $orderTotals->total_sub_total ?? 0,
            'discount'         => $orderTotals->total_discount ?? 0,
            'total'            => $orderTotals->total_amount ?? 0,
            'paid'             => $orderTotals->total_paid ?? 0,
            'due'              => $orderTotals->total_due ?? 0,
            'total_customer'   => Cache::remember('dashboard_customer_count', 300, fn() => Customer::count()),
            'total_order'      => $orderTotals->total_orders ?? 0,
            'total_product'    => Cache::remember('dashboard_product_count', 300, fn() => Product::count()),
            'total_sale_item'  => Cache::remember('dashboard_sale_items', 300, fn() => OrderProduct::sum('quantity')),
            'lowStockProducts' => Cache::remember('dashboard_low_stock', 120, fn() =>
                Product::where('quantity', '<=', 10)->where('status', 1)->orderBy('quantity', 'asc')->take(5)->get()
            ),
        ];

        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate   = Carbon::now()->format('Y-m-d');
        if ($request->has('daterange')) {
            $dates = explode(' to ', $request->query('daterange'));
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->format('Y-m-d');
                $endDate   = Carbon::parse($dates[1])->format('Y-m-d');
            }
        }

        $cacheKey    = "dashboard_daily_{$startDate}_{$endDate}";
        $dailyTotals = Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            return OrderTransaction::selectRaw('DATE(created_at) as date, SUM(amount) as total_amount')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get();
        });

        $data['dates']        = $dailyTotals->pluck('date')->toArray();
        $data['totalAmounts'] = $dailyTotals->pluck('total_amount')->toArray();
        $data['dateRange']    = 'from ' . $startDate . ' to ' . $endDate;

        $currentYear         = now()->year;
        $data['currentYear'] = $currentYear;

        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $monthExpression = 'strftime("%Y-%m", created_at)';
        } elseif ($driver === 'pgsql') {
            $monthExpression = "TO_CHAR(created_at, 'YYYY-MM')";
        } else {
            $monthExpression = 'DATE_FORMAT(created_at, "%Y-%m")';
        }

        $salesData = Cache::remember("dashboard_monthly_{$currentYear}", 300, function () use ($monthExpression, $currentYear) {
            return OrderTransaction::selectRaw($monthExpression . ' as month, SUM(amount) as total_amount')
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->pluck('total_amount', 'month')
                ->toArray();
        });

        $tempMonths           = [];
        $tempTotalAmountMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey               = Carbon::create($currentYear, $i, 1)->format('Y-m');
            $tempMonths[]           = $monthKey;
            $tempTotalAmountMonth[] = $salesData[$monthKey] ?? 0;
        }

        $data['months']           = $tempMonths;
        $data['totalAmountMonth'] = $tempTotalAmountMonth;

        return view('backend.index', $data);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('backend.profile.index', compact('user'));
    }
}
