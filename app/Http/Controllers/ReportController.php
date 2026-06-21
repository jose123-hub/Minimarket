<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Client;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date ?? now()->format('Y-m-d');
        
        $totalSales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $salesCount = Sale::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalPurchases = PurchaseOrder::whereBetween('created_at', [$startDate, $endDate])->sum('total');

        $inventoryValue = Product::selectRaw('SUM(stock * price) as value')->value('value') ?? 0;

        $salesByCategory = Category::withCount(['products as sales_count' => function($q) {
            $q->join('sale_details', 'products.id', '=', 'sale_details.product_id');
        }])->get();

        $recentSales = Sale::with(['customer', 'cashier', 'details'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        $weeklySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklySales[] = [
                'day'   => $date->format('D'),
                'sales' => Sale::whereDate('created_at', $date)->sum('total'),
                'purchases' => PurchaseOrder::whereDate('created_at', $date)->sum('total'),
            ];
        }

        $weeklySalesJson = json_encode($weeklySales, JSON_UNESCAPED_UNICODE);
        $salesByCategoryLabelsJson = json_encode($salesByCategory->pluck('name'));
        $salesByCategoryCountsJson = json_encode($salesByCategory->pluck('sales_count'));

        return view('admin.reports.index', compact(
            'totalSales', 'salesCount', 'totalPurchases', 'inventoryValue',
            'salesByCategory', 'recentSales', 'weeklySales', 'startDate', 'endDate',
            'weeklySalesJson', 'salesByCategoryLabelsJson', 'salesByCategoryCountsJson'
        ));
    }
}