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

        $rangeStart = \Carbon\Carbon::parse($startDate)->startOfDay();
        $rangeEnd   = \Carbon\Carbon::parse($endDate)->endOfDay();

        $totalSales = Sale::whereBetween('created_at', [$rangeStart, $rangeEnd])->sum('total');
        $salesCount = Sale::whereBetween('created_at', [$rangeStart, $rangeEnd])->count();

        $totalPurchases = PurchaseOrder::whereBetween('created_at', [$rangeStart, $rangeEnd])->sum('total');

        $inventoryValue = Product::selectRaw('SUM(stock * price) as value')->value('value') ?? 0;

        $salesByCategory = Category::all()->map(function ($category) use ($rangeStart, $rangeEnd) {
            $quantitySold = \App\Models\SaleDetail::join('products', 'sale_details.product_id', '=', 'products.id')
                ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
                ->where('products.category_id', $category->id)
                ->whereBetween('sales.created_at', [$rangeStart, $rangeEnd])
                ->sum('sale_details.quantity');

            return (object) [
                'name'        => $category->name,
                'sales_count' => $quantitySold,
            ];
        });

        $recentSales = Sale::with(['customer', 'cashier', 'details'])
            ->whereBetween('created_at', [$rangeStart, $rangeEnd])
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

        $salesDetailJson = json_encode($recentSales->map(function ($sale) {
            return [
                'invoice' => $sale->invoice_number ?? ('B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT)),
                'time'    => $sale->created_at->format('h:i A'),
                'items'   => $sale->details->count() . ' items',
                'method'  => ucfirst($sale->payment_method ?? 'Cash'),
                'cashier' => $sale->cashier?->name ?? '-',
                'total'   => (float) $sale->total,
            ];
        })->values());

        return view('admin.reports.index', compact(
            'totalSales', 'salesCount', 'totalPurchases', 'inventoryValue',
            'salesByCategory', 'recentSales', 'weeklySales', 'startDate', 'endDate',
            'weeklySalesJson', 'salesByCategoryLabelsJson', 'salesByCategoryCountsJson',
            'salesDetailJson'
        ));
    }
}