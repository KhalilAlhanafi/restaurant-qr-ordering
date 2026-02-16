<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\QRController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// QR Code Routes
Route::get('/scan/{token}', [QRController::class, 'scan'])->name('qr.scan');
Route::get('/qr-required', [QRController::class, 'required'])->name('qr.required');

// Menu Route (requires table identification)
Route::get('/menu', [MenuController::class, 'index'])
    ->middleware('identify.table')
    ->name('menu.index');

// Cart Routes (requires table identification)
Route::get('/cart', [CartController::class, 'index'])
    ->middleware('identify.table')
    ->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])
    ->middleware('identify.table')
    ->name('cart.add');
Route::put('/cart/update', [CartController::class, 'update'])
    ->middleware('identify.table')
    ->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])
    ->middleware('identify.table')
    ->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])
    ->middleware('identify.table')
    ->name('cart.clear');
Route::get('/cart/summary', [CartController::class, 'summary'])
    ->middleware('identify.table')
    ->name('cart.summary');

// Checkout Routes (requires table identification)
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->middleware('identify.table')
    ->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('identify.table')
    ->name('checkout.store');
Route::get('/order-confirmation/{order}', [CheckoutController::class, 'confirmation'])
    ->middleware('identify.table')
    ->name('order.confirmation');
Route::post('/checkout-finalize', [CheckoutController::class, 'checkout'])
    ->middleware('identify.table')
    ->name('checkout.finalize');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories CRUD
    Route::resource('categories', CategoryController::class);
    
    // Items CRUD
    Route::resource('items', ItemController::class)->except(['show']);
    
    // Tables CRUD
    Route::resource('tables', TableController::class)->except(['show']);
    
    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/reservations/timeline', [ReservationController::class, 'timeline'])->name('reservations.timeline');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/recent', [OrderController::class, 'recent'])->name('orders.recent');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/data', [OrderController::class, 'getOrderData'])->name('orders.data');
    Route::post('/orders/{order}/mark-seen', [OrderController::class, 'markAsSeen'])->name('orders.mark-seen');
    Route::post('/orders/{order}/items/{item}/mark-seen', [OrderController::class, 'markItemAsSeen'])->name('orders.mark-item-seen');
    Route::post('/orders/{order}/end-service', [OrderController::class, 'endService'])->name('orders.end-service');
    Route::get('/orders/{order}/add-items', [OrderController::class, 'addItems'])->name('orders.add-items');
    Route::post('/orders/{order}/add-items', [OrderController::class, 'storeItems'])->name('orders.store-items');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // QR Codes
    Route::get('/qr-codes', [QRController::class, 'generateAll'])->name('qr-codes');
    Route::get('/qr-code-image/{token}', [QRController::class, 'generateQrImage'])->name('qr-code-image');
});
