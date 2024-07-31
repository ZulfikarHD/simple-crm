<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/service', [ServiceController::class, 'index'])->name('service.index');
    Route::get('/service/create', [ServiceController::class, 'create'])->name('service.create');

    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

    Route::get('/product', [ProductController::class, 'index'])->name('product.index');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    Route::get('/setting', [SettingsController::class, 'index'])->name('setting.index');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    Route::resource('orders', OrderController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('inventory', InventoryController::class);

});

require __DIR__ . '/auth.php';
