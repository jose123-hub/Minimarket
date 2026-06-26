<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\Admin\ReturnApprovalController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () { return view('about'); });
Route::get('/services', function () { return view('services'); });
Route::get('/contact', function () { return view('contact'); });

Route::post('/contact', [ContactController::class, 'send'])
    ->name('contact.send');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('admin.purchases');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('admin.purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('admin.purchases.store');
    Route::get('/purchases/{purchase}/receive', [PurchaseController::class, 'receiveForm'])->name('admin.purchases.receive.form');
    Route::post('/purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('admin.purchases.receive');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('admin.purchases.show');
    Route::get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('admin.purchases.edit');
    Route::put('/purchases/{purchase}', [PurchaseController::class, 'update'])->name('admin.purchases.update');
    Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('admin.purchases.cancel');
    Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('admin.purchases.destroy');
    Route::resource('products', ProductController::class);
    Route::get('/promotions', [DiscountController::class, 'index'])->name('admin.promotions');
    Route::post('/promotions', [DiscountController::class, 'store'])->name('admin.promotions.store');
    Route::put('/promotions/{discount}', [DiscountController::class, 'update'])->name('admin.promotions.update');
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/rewards', [RewardController::class, 'index'])->name('admin.rewards');
    Route::post('/rewards', [RewardController::class, 'store'])->name('admin.rewards.store');
    Route::put('/rewards/{reward}', [RewardController::class, 'update'])->name('admin.rewards.update');
    Route::delete('/rewards/{reward}', [RewardController::class, 'destroy'])->name('admin.rewards.destroy');
    Route::get('/loyalty', [\App\Http\Controllers\Admin\LoyaltyOverviewController::class, 'index'])->name('admin.loyalty');
    Route::get('/loyalty/{client}', [\App\Http\Controllers\Admin\LoyaltyOverviewController::class, 'show'])->name('admin.loyalty.show');
    Route::get('/returns', [\App\Http\Controllers\Admin\ReturnApprovalController::class, 'index'])->name('admin.returns');
    Route::post('/returns/{return}/approve', [\App\Http\Controllers\Admin\ReturnApprovalController::class, 'approve'])->name('admin.returns.approve');
    Route::post('/returns/{return}/reject', [\App\Http\Controllers\Admin\ReturnApprovalController::class, 'reject'])->name('admin.returns.reject');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.pdf');
    Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('admin.reports.excel');
});

Route::middleware(['auth', 'cashier'])->prefix('cashier')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'cashierDashboard'])->name('cashier.dashboard');
    Route::get('/inventory', [ProductController::class, 'cashierInventory'])->name('cashier.inventory');
    Route::get('/sales/create', [SaleController::class, 'create'])
        ->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])
        ->name('sales.store');
    Route::get('/loyalty', [\App\Http\Controllers\LoyaltyController::class, 'index'])->name('cashier.loyalty');
    Route::post('/loyalty/earn', [\App\Http\Controllers\LoyaltyController::class, 'earn'])->name('cashier.loyalty.earn');
    Route::post('/loyalty/redeem', [\App\Http\Controllers\LoyaltyController::class, 'redeem'])->name('cashier.loyalty.redeem');
    Route::get('/cash', [CashController::class, 'index'])->name('cashier.cash');
    Route::post('/cash/open', [CashController::class, 'open'])->name('cashier.cash.open');
    Route::post('/cash/close', [CashController::class, 'close'])->name('cashier.cash.close');
    Route::get('/returns', [\App\Http\Controllers\ReturnController::class, 'index'])->name('cashier.returns');
    Route::get('/returns/create', [\App\Http\Controllers\ReturnController::class, 'create'])->name('cashier.returns.create');
    Route::get('/returns/sale-lookup/{sale}', [\App\Http\Controllers\ReturnController::class, 'saleLookup'])->name('cashier.returns.lookup');
    Route::post('/returns', [\App\Http\Controllers\ReturnController::class, 'store'])->name('cashier.returns.store');
});

Route::middleware(['auth'])->prefix('client')->group(function () {
    Route::get('/catalog', [\App\Http\Controllers\ClientController::class, 'catalog'])->name('client.catalog');
    Route::post('/orders', [\App\Http\Controllers\ClientController::class, 'store'])->name('client.orders.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';