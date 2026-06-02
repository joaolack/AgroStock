<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpirationDateController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
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

Route::get('/analytics', [AnalyticsController::class, 'index'])
    ->middleware(['auth'])
    ->name('analytics.index');

Route::get('/audit-logs', [AuditLogController::class, 'index'])
    ->middleware(['auth'])
    ->name('audit-logs.index');

Route::get('/expiration-date', [ExpirationDateController::class, 'index'])
    ->middleware(['auth'])
    ->name('expiration-date.index');

Route::post('/expiration-date/batches/{batch}/write-off', [ExpirationDateController::class, 'writeOffExpiredBatch'])
    ->middleware(['auth'])
    ->name('expiration-date.batches.write-off');

Route::get('/export', [ExportController::class, 'index'])
    ->middleware(['auth'])
    ->name('export.index');

Route::get('/export/reports/pdf', [ExportController::class, 'reportPdf'])
    ->middleware(['auth'])
    ->name('export.reports.pdf');

Route::get('/export/reports/preview', [ExportController::class, 'reportPreviewPdf'])
    ->middleware(['auth'])
    ->name('export.reports.preview');

Route::get('/export/reports/excel', [ExportController::class, 'reportExcel'])
    ->middleware(['auth'])
    ->name('export.reports.excel');

require __DIR__.'/auth.php';
