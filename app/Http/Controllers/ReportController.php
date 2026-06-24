<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Client;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
    $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
    $endDate = $request->input('end_date', now()->format('Y-m-d'));

    $rangeStart = Carbon::parse($startDate)->startOfDay();
    $rangeEnd = Carbon::parse($endDate)->endOfDay();

    $weeklyData = collect();

    $currentDate = $rangeStart->copy()->startOfDay();
    $lastDate = $rangeEnd->copy()->startOfDay();

    while ($currentDate->lte($lastDate)) {
    $dateForQuery = $currentDate->format('Y-m-d');

    $salesTotal = Sale::whereDate('created_at', $dateForQuery)
        ->sum('total');

    $purchasesTotal = PurchaseOrder::whereDate('created_at', $dateForQuery)
        ->sum('total');

    $weeklyData->push([
        'day' => $currentDate->format('d/m'),
        'sales' => (float) $salesTotal,
        'purchases' => (float) $purchasesTotal,
    ]);

    $currentDate->addDay();
    }

    $weeklySalesJson = json_encode($weeklyData->values(), JSON_UNESCAPED_UNICODE);

    $totalSales = Sale::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->sum('total');

    $salesCount = Sale::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->count();

    $recentSales = Sale::with(['details', 'cashier'])
        ->whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->latest()
        ->take(10)
        ->get();

    $averageTicket = $salesCount > 0 ? $totalSales / $salesCount : 0;

    $totalPurchases = PurchaseOrder::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->sum('total');

    $inventoryValue = Product::selectRaw('SUM(stock * cost) as value')
        ->value('value') ?? 0;

    $totalCustomers = Client::count();

    $salesByCategory = SaleDetail::join('products', 'sale_details.product_id', '=', 'products.id')
        ->join('categories as child_categories', 'products.category_id', '=', 'child_categories.id')
        ->leftJoin('categories as parent_categories', 'child_categories.parent_id', '=', 'parent_categories.id')
        ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
        ->whereBetween('sales.created_at', [$rangeStart, $rangeEnd])
        ->selectRaw('COALESCE(parent_categories.name, child_categories.name) as name')
        ->selectRaw('SUM(sale_details.subtotal) as total_sales')
        ->groupByRaw('COALESCE(parent_categories.name, child_categories.name)')
        ->orderByDesc('total_sales')
        ->get();

    $salesByCategoryLabelsJson = json_encode(
    $salesByCategory->pluck('name')->values(),
       JSON_UNESCAPED_UNICODE
    );

    $salesByCategoryAmountsJson = json_encode(
    $salesByCategory->pluck('total_sales')->map(fn ($value) => (float) $value)->values(),
      JSON_UNESCAPED_UNICODE
    );

    $purchaseOrdersCount = PurchaseOrder::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->count();

    $pendingPurchases = PurchaseOrder::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->where('status', 'pending')
        ->count();

    $partialPurchases = PurchaseOrder::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->where('status', 'partial')
        ->count();

    $receivedPurchases = PurchaseOrder::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->where('status', 'received')
        ->count();

    $purchasesBySupplier = Supplier::all()->map(function ($supplier) use ($rangeStart, $rangeEnd) {
    $total = PurchaseOrder::where('supplier_id', $supplier->id)
        ->whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->sum('total');

    return [
        'name' => $supplier->company_name,
        'total' => (float) $total,
     ];
    })->filter(fn ($item) => $item['total'] > 0)->values();

    $recentPurchases = PurchaseOrder::with('supplier')
        ->whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->latest()
        ->take(10)
        ->get();

    $purchasesBySupplierJson = json_encode($purchasesBySupplier, JSON_UNESCAPED_UNICODE);

    $totalProducts = Product::count();

    $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
        ->count();

    $outOfStockProducts = Product::where('stock', '<=', 0)
        ->count();

    $normalStockProducts = max($totalProducts - $lowStockProducts, 0);

    $stockByCategory = Product::join('categories as child_categories', 'products.category_id', '=', 'child_categories.id')
        ->leftJoin('categories as parent_categories', 'child_categories.parent_id', '=', 'parent_categories.id')
        ->selectRaw('COALESCE(parent_categories.name, child_categories.name) as name')
        ->selectRaw('SUM(products.stock) as total_stock')
        ->groupByRaw('COALESCE(parent_categories.name, child_categories.name)')
        ->orderByDesc('total_stock')
        ->get();

    $lowStockList = Product::with('category')
        ->whereColumn('stock', '<=', 'min_stock')
        ->orderBy('stock', 'asc')
        ->take(10)
        ->get();

    $stockByCategoryJson = json_encode(
    $stockByCategory->map(function ($item) {
        return [
            'name' => $item->name,
            'stock' => (int) $item->total_stock,
        ];
       })->values(),
      JSON_UNESCAPED_UNICODE
    );

    $activeCustomers = Sale::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->whereNotNull('customer_id')
        ->distinct('customer_id')
        ->count('customer_id');

    $inactiveCustomers = max($totalCustomers - $activeCustomers, 0);

    $totalStars = Client::sum('accumulated_stars');

    $newCustomers = Client::whereBetween('created_at', [$rangeStart, $rangeEnd])
        ->count();

    $topCustomers = Client::orderByDesc('accumulated_stars')
        ->take(10)
        ->get();

    $topCustomersJson = json_encode(
    $topCustomers->map(function ($client) {
        return [
            'name' => $client->name,
            'stars' => (int) $client->accumulated_stars,
        ];
         })->values(),
         JSON_UNESCAPED_UNICODE
        );

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
       'startDate',
       'endDate',
       'totalSales',
       'salesCount',
       'averageTicket',
       'recentSales',
       'totalPurchases',
       'inventoryValue',
       'totalCustomers',
       'weeklySalesJson',
       'salesDetailJson',

       'salesByCategoryLabelsJson',
       'salesByCategoryAmountsJson',

       'purchaseOrdersCount',
       'pendingPurchases',
       'partialPurchases',
       'receivedPurchases',
       'purchasesBySupplier',
       'recentPurchases',
       'purchasesBySupplierJson',

       'totalProducts',
       'lowStockProducts',
       'outOfStockProducts',
       'normalStockProducts',
       'stockByCategory',
       'stockByCategoryJson',
       'lowStockList',

       'activeCustomers',
       'inactiveCustomers',
       'totalStars',
       'newCustomers',
       'topCustomers',
       'topCustomersJson'
        ));
    }
    private function getReportDates(Request $request): array
{
    $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
    $endDate = $request->input('end_date', now()->format('Y-m-d'));

    $rangeStart = Carbon::parse($startDate)->startOfDay();
    $rangeEnd = Carbon::parse($endDate)->endOfDay();

    return [$startDate, $endDate, $rangeStart, $rangeEnd];
}

public function exportPdf(Request $request)
{
    [$startDate, $endDate, $rangeStart, $rangeEnd] = $this->getReportDates($request);

    $type = $request->input('type', 'sales');

    if (! in_array($type, ['sales', 'purchases', 'inventory', 'customers'])) {
        abort(404);
    }

    switch ($type) {
        case 'sales':
            $sales = Sale::with(['details', 'cashier'])
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->latest()
                ->get();

            $pdf = Pdf::loadView('admin.reports.exports.sales-pdf', compact(
                'sales',
                'startDate',
                'endDate'
            ));

            return $pdf->download("sales_report_{$startDate}_to_{$endDate}.pdf");

        case 'purchases':
            $purchases = PurchaseOrder::with('supplier')
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->latest()
                ->get();

            $pdf = Pdf::loadView('admin.reports.exports.purchases-pdf', compact(
                'purchases',
                'startDate',
                'endDate'
            ));

            return $pdf->download("purchases_report_{$startDate}_to_{$endDate}.pdf");

        case 'inventory':
            $products = Product::with('category')
                ->orderBy('stock', 'asc')
                ->get();

            $pdf = Pdf::loadView('admin.reports.exports.inventory-pdf', compact(
                'products',
                'startDate',
                'endDate'
            ));

            return $pdf->download("inventory_report_{$startDate}_to_{$endDate}.pdf");

        case 'customers':
            $customers = Client::orderByDesc('accumulated_stars')
                ->get();

            $pdf = Pdf::loadView('admin.reports.exports.customers-pdf', compact(
                'customers',
                'startDate',
                'endDate'
            ));

            return $pdf->download("customers_report_{$startDate}_to_{$endDate}.pdf");
    }
}

public function exportExcel(Request $request)
{
    [$startDate, $endDate, $rangeStart, $rangeEnd] = $this->getReportDates($request);

    $type = $request->input('type', 'sales');

    if (! in_array($type, ['sales', 'purchases', 'inventory', 'customers'])) {
        abort(404);
    }

    $fileName = "{$type}_report_{$startDate}_to_{$endDate}.csv";

    return response()->streamDownload(function () use ($type, $rangeStart, $rangeEnd) {
        $file = fopen('php://output', 'w');

        fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        switch ($type) {
            case 'sales':
                $sales = Sale::with(['details', 'cashier'])
                    ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                    ->latest()
                    ->get();

                fputcsv($file, ['SALES REPORT'], ';');
                fputcsv($file, ['Invoice', 'Date', 'Items', 'Cashier', 'Payment method', 'Total'], ';');

                foreach ($sales as $sale) {
                    fputcsv($file, [
                        $sale->invoice_number ?? 'B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT),
                        $sale->created_at->format('d/m/Y H:i'),
                        $sale->details->count(),
                        $sale->cashier?->name ?? '-',
                        ucfirst($sale->payment_method ?? 'Cash'),
                        number_format($sale->total, 2),
                    ], ';');
                }
                break;

            case 'purchases':
                $purchases = PurchaseOrder::with('supplier')
                    ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                    ->latest()
                    ->get();

                fputcsv($file, ['PURCHASES REPORT'], ';');
                fputcsv($file, ['Order', 'Supplier', 'Status', 'Date', 'Total'], ';');

                foreach ($purchases as $purchase) {
                    fputcsv($file, [
                        'PO-' . str_pad($purchase->id, 5, '0', STR_PAD_LEFT),
                        $purchase->supplier?->company_name ?? '-',
                        ucfirst($purchase->status),
                        $purchase->created_at->format('d/m/Y'),
                        number_format($purchase->total, 2),
                    ], ';');
                }
                break;

            case 'inventory':
                $products = Product::with('category')
                    ->orderBy('stock', 'asc')
                    ->get();

                fputcsv($file, ['INVENTORY REPORT'], ';');
                fputcsv($file, ['Product', 'Category', 'Current stock', 'Minimum stock', 'Status'], ';');

                foreach ($products as $product) {
                    fputcsv($file, [
                        $product->name,
                        $product->category?->name ?? '-',
                        $product->stock,
                        $product->min_stock,
                        $product->stock <= 0 ? 'Out of stock' : ($product->stock <= $product->min_stock ? 'Low stock' : 'Normal stock'),
                    ], ';');
                }
                break;

            case 'customers':
                $customers = Client::orderByDesc('accumulated_stars')
                    ->get();

                fputcsv($file, ['CUSTOMERS REPORT'], ';');
                fputcsv($file, ['Customer', 'Email', 'Stars', 'Registered'], ';');

                foreach ($customers as $client) {
                    $clientName = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));

                    fputcsv($file, [
                        $clientName ?: ($client->name ?? '-'),
                        $client->email ?? '-',
                        $client->accumulated_stars ?? 0,
                        $client->created_at?->format('d/m/Y') ?? '-',
                    ], ';');
                }
                break;
        }

        fclose($file);
    }, $fileName, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
}