<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Dashboard view
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// API Routes untuk dashboard (nanti bisa dipindah ke routes/api.php)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/rawat-jalan', [DashboardController::class, 'getRawatJalanData'])->name('rawat-jalan');
    Route::get('/rawat-inap', [DashboardController::class, 'getRawatInapData'])->name('rawat-inap');
    Route::get('/total', [DashboardController::class, 'getTotalData'])->name('total');
});