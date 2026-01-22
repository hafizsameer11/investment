<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\GoalsController;
use App\Http\Controllers\Dashboard\PlansController;
use App\Http\Controllers\Dashboard\InvestmentController;
use App\Http\Controllers\Admin\MiningPlanController;
use App\Http\Controllers\Dashboard\WalletController;
use App\Http\Controllers\Admin\RewardLevelController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SupportController;
use App\Http\Controllers\Dashboard\TargetsController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ReferralsController;
use App\Http\Controllers\Dashboard\TransactionsController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Admin\EarningCommissionController;
use App\Http\Controllers\Admin\CurrencyConversionController;
use App\Http\Controllers\Admin\DepositPaymentMethodController;
use App\Http\Controllers\Admin\InvestmentCommissionController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Dashboard\WithdrawSecurityController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

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

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/terms', [AuthController::class, 'showTerms'])->name('terms');
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Routes - Protected with auth middleware
Route::prefix('user/dashboard')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/claim-all-earnings', [DashboardController::class, 'claimAllEarnings'])->name('dashboard.claim-all-earnings');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/deposit', [WalletController::class, 'deposit'])->name('deposit.index');
    Route::get('/deposit/confirm', [WalletController::class, 'depositConfirm'])->name('deposit.confirm');
    Route::post('/deposit', [WalletController::class, 'storeDeposit'])->name('deposit.store');
    Route::get('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw.index');
    Route::get('/withdraw/confirm', [WalletController::class, 'withdrawConfirm'])->name('withdraw.confirm');
    Route::post('/withdraw', [WalletController::class, 'storeWithdrawal'])->name('withdraw.store');
    Route::get('/plans', [PlansController::class, 'index'])->name('plans.index');

    // Investment Routes
    Route::get('/investments/modal/{planId}', [InvestmentController::class, 'showModal'])->name('investments.modal');
    Route::get('/investments/manage/{planId}', [InvestmentController::class, 'showManageModal'])->name('investments.manage');
    Route::get('/investments/{investment}/claim-modal', [InvestmentController::class, 'showClaimModal'])->name('investments.claim-modal');
    Route::post('/investments', [InvestmentController::class, 'store'])->name('investments.store');
    Route::post('/investments/{investment}/claim', [InvestmentController::class, 'claimEarnings'])->name('investments.claim');
    Route::post('/investments/{investment}/update', [InvestmentController::class, 'updateInvestment'])->name('investments.update');

    Route::get('/goals', [GoalsController::class, 'index'])->name('goals.index');
    Route::post('/goals/{levelId}/claim', [GoalsController::class, 'claimReward'])->name('goals.claim');
    Route::get('/targets', [TargetsController::class, 'index'])->name('targets.index');
    Route::get('/referrals', [ReferralsController::class, 'index'])->name('referrals.index');
    Route::get('/referrals/claim-earnings', [ReferralsController::class, 'claimEarningsPage'])->name('referrals.claim-earnings');
    Route::post('/referrals/claim', [ReferralsController::class, 'claimEarnings'])->name('referrals.claim');
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationsController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update-name', [ProfileController::class, 'updateName'])->name('profile.update-name');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/withdraw-security', [WithdrawSecurityController::class, 'index'])->name('withdraw-security.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/password/reset', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Home route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Admin Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'authenticate'])->name('admin.login.post');
});

// Admin Logout Route
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// Admin Routes - Protected with admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.index');
    Route::get('/form', [HomeController::class, 'form'])->name('admin.form');
    Route::get('/table', [HomeController::class, 'table'])->name('admin.table');
    Route::get('/financial-data', [HomeController::class, 'getFinancialData'])->name('admin.financial-data');

    // Admin Investment Commission Routes
    Route::prefix('investment-commission')->name('admin.investment-commission.')->group(function () {
    Route::get('/', [InvestmentCommissionController::class, 'index'])->name('index');
    Route::get('/create', [InvestmentCommissionController::class, 'create'])->name('create');
    Route::post('/', [InvestmentCommissionController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [InvestmentCommissionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [InvestmentCommissionController::class, 'update'])->name('update');
    Route::delete('/{id}', [InvestmentCommissionController::class, 'destroy'])->name('destroy');
});

    // Admin Earning Commission Routes
    Route::prefix('earning-commission')->name('admin.earning-commission.')->group(function () {
    Route::get('/', [EarningCommissionController::class, 'index'])->name('index');
    Route::get('/create', [EarningCommissionController::class, 'create'])->name('create');
    Route::post('/', [EarningCommissionController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [EarningCommissionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [EarningCommissionController::class, 'update'])->name('update');
    Route::delete('/{id}', [EarningCommissionController::class, 'destroy'])->name('destroy');
});

    // Admin Mining Plan Routes
    Route::prefix('mining-plan')->name('admin.mining-plan.')->group(function () {
    Route::get('/', [MiningPlanController::class, 'index'])->name('index');
    Route::get('/create', [MiningPlanController::class, 'create'])->name('create');
    Route::post('/', [MiningPlanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [MiningPlanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MiningPlanController::class, 'update'])->name('update');
    Route::delete('/{id}', [MiningPlanController::class, 'destroy'])->name('destroy');
});

    // Admin Reward Level Routes
    Route::prefix('reward-level')->name('admin.reward-level.')->group(function () {
        Route::get('/', [RewardLevelController::class, 'index'])->name('index');
        Route::get('/create', [RewardLevelController::class, 'create'])->name('create');
        Route::post('/', [RewardLevelController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RewardLevelController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RewardLevelController::class, 'update'])->name('update');
        Route::delete('/{id}', [RewardLevelController::class, 'destroy'])->name('destroy');
    });

    // Admin User Management Routes
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Admin Deposit Payment Method Routes
    Route::prefix('deposit-payment-method')->name('admin.deposit-payment-method.')->group(function () {
        Route::get('/', [DepositPaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [DepositPaymentMethodController::class, 'create'])->name('create');
        Route::post('/', [DepositPaymentMethodController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [DepositPaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DepositPaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{id}', [DepositPaymentMethodController::class, 'destroy'])->name('destroy');
    });

    // Admin Currency Conversion Routes
    Route::prefix('currency-conversion')->name('admin.currency-conversion.')->group(function () {
        Route::get('/', [CurrencyConversionController::class, 'index'])->name('index');
        Route::get('/create', [CurrencyConversionController::class, 'create'])->name('create');
        Route::post('/', [CurrencyConversionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CurrencyConversionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CurrencyConversionController::class, 'update'])->name('update');
        Route::delete('/{id}', [CurrencyConversionController::class, 'destroy'])->name('destroy');
    });

    // Admin Deposit Management Routes
    Route::prefix('deposits')->name('admin.deposits.')->group(function () {
        Route::get('/', [DepositController::class, 'index'])->name('index');
        Route::get('/{id}', [DepositController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [DepositController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [DepositController::class, 'reject'])->name('reject');
    });

    // Admin Withdrawal Management Routes
    Route::prefix('withdrawals')->name('admin.withdrawals.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::get('/{id}', [WithdrawalController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [WithdrawalController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [WithdrawalController::class, 'reject'])->name('reject');
    });

    // Admin Notification Routes
    Route::prefix('notifications')->name('admin.notifications.')->group(function () {
        Route::get('/create', [NotificationController::class, 'index'])->name('create');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
    });
});

// Database Seeder Route (for production use)
// Access: /run-seeders?token=YOUR_SECRET_TOKEN
Route::get('/run-seeders', function () {
    $token = request()->query('token');
    $secretToken = env('SEEDER_SECRET_TOKEN', 'change-this-secret-token-in-production');

    // Check if token matches
    if ($token !== $secretToken) {
        return response()->json([
            'error' => 'Unauthorized. Invalid token.',
        ], 401);
    }

    try {
        // Run all seeders
        Artisan::call('db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => true,
        ]);

        $output = \Illuminate\Support\Facades\Artisan::output();

        return response()->json([
            'success' => true,
            'message' => 'All seeders have been run successfully.',
            'output' => $output,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to run seeders: ' . $e->getMessage(),
        ], 500);
    }
})->name('run.seeders');

Route::get('/optimize-app', function () {
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    Artisan::call('optimize');

    return "Application optimized and caches cleared successfully!";
});
Route::get('/migrate', function () {
    Artisan::call('migrate');
    return response()->json(['message' => 'Migration successful'], 200);
});
Route::get('/migrate/rollback', function () {
    Artisan::call('migrate:rollback');
    return response()->json(['message' => 'Migration rollback successfully'], 200);
});
