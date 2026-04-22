<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/import', [ImportController::class, 'storeTransactions'])->name('import.storeTransactions');

Route::get('/', [DashboardController::class, 'index']);

Route::view('settings', 'settings')->name('settings');
Route::view('dashboard', 'dashboard')->name('dashboard');


Route::get('/', function () {
    return view('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transactions/add-transaction', [TransactionController::class, 'add_transaction'])->name('transaction.add');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
});
require __DIR__ . '/settings.php';
