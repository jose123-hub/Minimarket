<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get(
            '/sales',
            [SaleController::class, 'index']
        )->name('admin.sales');
});

Route::middleware(['auth', 'cashier'])->prefix('cashier')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'cashierDashboard'])->name('cashier.dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('cashier.products');
    Route::get('/sales/create', [SaleController::class, 'create'])
        ->name('sales.create');

    Route::post('/sales', [SaleController::class, 'store'])
        ->name('sales.store');
});

Route::middleware(['auth'])->prefix('client')->group(function () {
    Route::get('/catalog', [ProductController::class, 'index'])->name('client.catalog');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';