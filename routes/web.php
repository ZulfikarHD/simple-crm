<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoyalityController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SegmentsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
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

    // Route::get('/report', [ReportController::class, 'index'])->name('report.index');


    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');


    Route::resource('orders', OrderController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('reports', ReportController::class);

    Route::get('/customers/segments', [SegmentsController::class, 'index'])->name('customers.segments');
    Route::get('/customers/loyality', [LoyalityController::class, 'index'])->name('customers.loyality');
    Route::get('/inventory/suppliers', [SuppliersController::class, 'index'])->name('suppliers.index');
    Route::get('/inventory/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');

    Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');
    Route::get('/reports/customers', [CustomersReportController::class, 'index'])->name('reports.customers');
    Route::get('/reports/inventory', [InventoryReportController::class, 'index'])->name('reports.inventory');

    Route::get('/setting', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/user-management', [UserController::class, 'index'])->name('user-management.index');
    Route::get('/user-roles',[UserRoleController::class, 'index'])->name('user-roles.index');
    Route::get('/business-management', [BusinessController::class, 'index'])->name('business-management.index');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/create', [OrderController::class, 'store'])->name('orders.store');

    Route::get('/payment/create/{orderId}',[PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payment/create',[PaymentController::class, 'store'])->name('payments.store');

});

require __DIR__ . '/auth.php';
