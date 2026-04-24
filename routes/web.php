<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/import', [ImportController::class, 'storeTransactions'])->name('import.storeTransactions');

Route::get('/', [DashboardController::class, 'index']);

Route::view('dashboard', 'dashboard')->name('dashboard');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');


Route::get('/', function () {
    return view('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/add-transaction', [TransactionController::class, 'add_transaction'])->name('transaction.add');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
});
require __DIR__ . '/settings.php';
