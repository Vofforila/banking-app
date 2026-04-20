<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/import', [ImportController::class, 'store'])->name('import.store');

Route::get('/', [DashboardController::class, 'index']);
Route::get('/transactions', [TransactionController::class, 'index']);

Route::view('settings', 'settings')->name('settings');

Route::view('dashboard', 'dashboard')->name('dashboard');
Route::get('/', function () {
    return view('dashboard');
    Route::middleware(['auth', 'verified'])->group(function () {
    });
});

require __DIR__ . '/settings.php';
