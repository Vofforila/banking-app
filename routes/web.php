<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/transactions', [TransactionController::class, 'index']);

Route::view('settingss', 'settingss')->name('settingss');

Route::view('dashboard', 'dashboard')->name('dashboard');
// Route::middleware(['auth', 'verified'])->group(function () {
// });

require __DIR__.'/settings.php';
