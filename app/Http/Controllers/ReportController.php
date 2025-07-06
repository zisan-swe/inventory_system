<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function show(Request $request, $reportType)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        switch ($reportType) {
            case 'sales':
                return $this->salesReport($request);
            case 'inventory':
                return $this->inventoryReport($request);
            default:
                abort(404, 'Report not found');
        }
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $sales = Sale::with(['details.product', 'payments'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        $totalSales = $sales->sum('grand_total');
        $totalPaid = $sales->sum('paid_amount');
        $totalDue = $sales->sum('due_amount');

        return view('reports.sales', compact('sales', 'startDate', 'endDate', 'totalSales', 'totalPaid', 'totalDue'));
    }

    public function inventoryReport(Request $request)
    {
        $products = Product::with(['saleDetails' => function ($query) use ($request) {
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereHas('sale', function ($q) use ($request) {
                    $q->whereBetween('date', [
                        $request->input('start_date'),
                        $request->input('end_date')
                    ]);
                });
            }
        }])->get();

        return view('reports.inventory', compact('products'));
    }
}
