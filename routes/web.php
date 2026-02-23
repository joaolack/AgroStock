<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::resource('products', ProductController::class)->middleware(['auth']);
Route::post('products/{product}/move', [ProductController::class, 'moveStock'])
    ->name('products.moveStock');

Route::resource('categories', CategoryController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
