<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
    $totalSales = Sale::whereDate('created_at', today())
    ->sum('total');
    $totalProducts = Product::count();

    $totalCategories = Category::whereNull('parent_id')->count();
    $totalSubcategories = Category::whereNotNull('parent_id')->count();

    $totalUsers = User::count();

    $lowStock = Product::with('category')
        ->where('stock', '<', 10)
        ->get();

    $recentProducts = Product::with('category')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard', compact(
        'totalSales',
        'totalProducts',
        'totalCategories',
        'totalSubcategories',
        'totalUsers',
        'lowStock',
        'recentProducts'
    ));
    }
    public function cashierDashboard()
    {
    $todaySales = Sale::whereDate('created_at', today())
        ->where('cashier_id', Auth::id())
        ->get();

    $totalToday = $todaySales->sum('total');
    $transactionsToday = $todaySales->count();

    $recentSales = Sale::with(['customer'])
        ->where('cashier_id', Auth::id())
        ->latest()
        ->take(6)
        ->get();

    $pendingOrders = Sale::with(['customer', 'details'])
        ->whereNotNull('receipt_number')
        ->where('payment_status', 'paid')
        ->whereIn('order_status', ['pending', 'preparing', 'ready'])
        ->latest()
        ->get()
        ->map(function ($sale) {
            return [
                'id'            => $sale->id,
                'customer_name' => $sale->customer?->name ?? 'Customer',
                'total'         => $sale->total,
                'items_count'   => $sale->details->count(),
                'time'          => $sale->created_at->format('h:i A'),
                'status'        => $sale->order_status,
            ];
        });

    return view('cashier.dashboard', compact(
        'totalToday',
        'transactionsToday',
        'recentSales',
        'pendingOrders'
    ));
    }
}