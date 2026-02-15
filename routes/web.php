<?php

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

// Admin QR Code Generation
Route::get('/admin/qr-codes', [QRController::class, 'generateAll'])
    ->name('admin.qr-codes');
Route::get('/admin/qr-code-image/{token}', [QRController::class, 'generateQrImage'])
    ->name('admin.qr-code-image');
