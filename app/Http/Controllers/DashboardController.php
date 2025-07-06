<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{



    public function dashboard()
    {

        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $thirtyDaysAgo = Carbon::now()->subDays(30);


        $totalSales = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('grand_total');

        $totalRevenue = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('paid_amount');

        $totalDue = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('due_amount');

        $salesCount = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->count();
        $dueCount = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('due_amount', '>', 0)
            ->count();

        $paidPercentage = $salesCount > 0
            ? round(($salesCount - $dueCount) / $salesCount * 100, 2)
            : 0;


        $productCount = Product::count();
        $inventoryValue = Product::sum(DB::raw('purchase_price * current_stock'));

        $lowStockProducts = Product::where('current_stock', '<', 10)
            ->orderBy('current_stock')
            ->limit(5)
            ->get();


        $topProducts = Product::withSum('saleDetails as total_sold', 'quantity')
            ->withSum('saleDetails as total_revenue', 'total_price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();


        $recentSales = Sale::with('details')
            ->latest()
            ->limit(5)
            ->get();

        $salesChart = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $salesChart['labels'][] = $date->format('M d');

            $dailySales = Sale::whereDate('date', $date)
                ->sum('grand_total');

            $salesChart['data'][] = $dailySales;
        }

        return view('dashboard', compact(
            'totalSales',
            'totalRevenue',
            'totalDue',
            'salesCount',
            'dueCount',
            'paidPercentage',
            'productCount',
            'inventoryValue',
            'lowStockProducts',
            'topProducts',
            'recentSales',
            'salesChart'
        ));
    }
}
