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
    $totalProducts = Product::count();

    $totalCategories = Category::count();

    $totalUsers = User::count();

    $totalSales = Sale::count();

    $totalRevenue = Sale::sum('total');

    $recentSales = Sale::with([
        'customer',
        'cashier'
    ])
    ->latest()
    ->take(5)
    ->get();

    $recentProducts = Product::latest()
        ->take(5)
        ->get();

    $lowStock = Product::where('stock', '<=', 10)
        ->get();

    return view('dashboard', compact(
        'totalProducts',
        'totalCategories',
        'totalUsers',
        'totalSales',
        'totalRevenue',
        'recentSales',
        'recentProducts',
        'lowStock'
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
        ->where('status', 'pending')
        ->latest()
        ->get()
        ->map(function($sale) {
            return [
                'id'            => $sale->id,
                'customer_name' => $sale->customer?->name ?? 'Guest',
                'total'         => $sale->total,
                'items_count'   => $sale->details->count(),
                'time'          => $sale->created_at->format('h:i A'),
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