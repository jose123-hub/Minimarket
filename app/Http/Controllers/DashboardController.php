<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Sale;

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
}