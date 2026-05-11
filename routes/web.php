<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\WaiterCallController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// QR Code Routes
Route::get('/scan/{token}', [QRController::class, 'scan'])
    ->middleware('web')
    ->name('qr.scan');
Route::get('/qr-required', [QRController::class, 'required'])
    ->middleware('web')
    ->name('qr.required');
Route::get('/language', [QRController::class, 'setLanguage'])
    ->middleware('web')
    ->name('language.set');

// Menu Route (requires table identification)
Route::get('/menu', [MenuController::class, 'index'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('menu.index');

// Cart Routes (requires table identification)
Route::get('/cart', [CartController::class, 'index'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('cart.add');
Route::put('/cart/update', [CartController::class, 'update'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('cart.clear');
Route::get('/cart/summary', [CartController::class, 'summary'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('cart.summary');

// Checkout Routes (requires table identification)
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('checkout.store');
Route::get('/order-confirmation/{order}', [CheckoutController::class, 'confirmation'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('order.confirmation');
Route::post('/checkout-finalize', [CheckoutController::class, 'checkout'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('checkout.finalize');
Route::post('/order-rate', [RatingController::class, 'store'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('order.rate');

// Call Waiter (customer side)
Route::post('/call-waiter', [WaiterCallController::class, 'store'])
    ->middleware(['identify.table', 'set.locale'])
    ->name('waiter.call');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    // Admin-only Routes (require admin role)
    Route::middleware(['admin.auth'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Categories CRUD
        Route::resource('categories', CategoryController::class);

        // Items CRUD
        Route::resource('items', ItemController::class)->except(['show']);
        Route::post('/items/{item}/toggle-availability', [ItemController::class, 'toggleAvailability'])->name('items.toggle-availability');

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
        Route::post('/orders/{order}/print-receipt', [\App\Http\Controllers\Admin\PrintController::class, 'printReceipt'])->name('orders.print-receipt');
        Route::post('/orders/{order}/print-kitchen', [\App\Http\Controllers\Admin\PrintController::class, 'printKitchen'])->name('orders.print-kitchen');
        Route::post('/orders/{order}/print-station/{station}', [\App\Http\Controllers\Admin\PrintController::class, 'printStation'])->name('orders.print-station');

        // QR Codes
        Route::get('/qr-codes', [QRController::class, 'generateAll'])->name('qr-codes');
        Route::get('/qr-code-image/{token}', [QRController::class, 'generateQrImage'])->name('qr-code-image');

        // Taxes
        Route::resource('taxes', TaxController::class);

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

        // Ratings
        Route::resource('ratings', \App\Http\Controllers\Admin\RatingController::class);

        // Waiter Calls
        Route::get('/waiter-calls/pending', [WaiterCallController::class, 'pending'])->name('waiter-calls.pending');
        Route::post('/waiter-calls/{waiterCall}/resolve', [WaiterCallController::class, 'resolve'])->name('waiter-calls.resolve');
    });

    // Station Routes (require station auth - admin or matching station user)
    Route::middleware(['station.auth'])->group(function () {
        Route::get('/stations/{station}', [\App\Http\Controllers\Admin\StationController::class, 'index'])->name('stations.index');
        Route::get('/stations/{station}/data', [\App\Http\Controllers\Admin\StationController::class, 'getOrders'])->name('stations.data');
        Route::post('/stations/{order}/items/{item}/mark-seen', [\App\Http\Controllers\Admin\OrderController::class, 'markItemAsSeen'])->name('stations.mark-item-seen');
    });
});
