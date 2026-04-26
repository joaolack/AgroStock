<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::resource('products', ProductController::class)->middleware(['auth']);

Route::resource('categories', CategoryController::class)->middleware(['auth']);

Route::resource('suppliers', SupplierController::class)->middleware(['auth']);

Route::get('stock-movements', [StockMovementController::class, 'index'])
    ->middleware(['auth'])
    ->name('stock-movements.index');

Route::post('stock-movements', [StockMovementController::class, 'store'])
    ->middleware(['auth'])
    ->name('stock-movements.store');

Route::get('/charts', function () {
    return view('charts.index');
})->name('charts.index');

Route::get('/expiration-date', function () {
    return view('expiration_date.index');
})->name('expiration-date.index');

Route::get('/export', function () {
    return view('export.index');
})->name('export.index');


require __DIR__.'/auth.php';
