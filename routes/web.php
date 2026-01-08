<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\WalletController;
use App\Http\Controllers\Dashboard\PlansController;
use App\Http\Controllers\Dashboard\GoalsController;
use App\Http\Controllers\Dashboard\TargetsController;
use App\Http\Controllers\Dashboard\ReferralsController;
use App\Http\Controllers\Dashboard\TransactionsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SupportController;
use App\Http\Controllers\Dashboard\WithdrawSecurityController;
use App\Http\Controllers\Dashboard\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes (Frontend Only - No Backend Logic)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Dashboard Routes (Frontend Only) - Using user/dashboard/ prefix
Route::prefix('user/dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/deposit', [WalletController::class, 'deposit'])->name('deposit.index');
    Route::get('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw.index');
    Route::get('/plans', [PlansController::class, 'index'])->name('plans.index');
    Route::get('/goals', [GoalsController::class, 'index'])->name('goals.index');
    Route::get('/targets', [TargetsController::class, 'index'])->name('targets.index');
    Route::get('/referrals', [ReferralsController::class, 'index'])->name('referrals.index');
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/withdraw-security', [WithdrawSecurityController::class, 'index'])->name('withdraw-security.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});

// Placeholder routes (to be implemented later)
Route::get('/password/reset', function () {
    return redirect()->route('login')->with('info', 'Password reset will be available soon.');
})->name('password.request');

// Home route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});
