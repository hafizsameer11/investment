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
use App\Http\Controllers\Admin\CryptoWalletController;
use App\Http\Controllers\Dashboard\WithdrawSecurityController;
use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
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

// Under Construction Page - Active Route
Route::get('/under-construction', function () {
    return view('under-construction');
})->name('under-construction');

// Catch-all route - redirect all requests to under construction
Route::fallback(function () {
    return redirect()->route('under-construction');
});

// ============================================================================
// ALL ROUTES BELOW ARE COMMENTED OUT - SITE IS UNDER CONSTRUCTION
// ============================================================================

/*
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
    Route::get('/referral-activities', [DashboardController::class, 'getReferralActivitiesAjax'])->name('dashboard.referral-activities');
    Route::post('/claim-all-earnings', [DashboardController::class, 'claimAllEarnings'])->name('dashboard.claim-all-earnings');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/deposit', [WalletController::class, 'deposit'])->name('deposit.index');
    Route::get('/deposit/confirm', [WalletController::class, 'depositConfirm'])->name('deposit.confirm');
    Route::get('/deposit/crypto/network', [WalletController::class, 'cryptoDepositNetwork'])->name('deposit.crypto.network');
    Route::get('/deposit/crypto/confirm', [WalletController::class, 'cryptoDepositConfirm'])->name('deposit.crypto.confirm');
    Route::post('/deposit', [WalletController::class, 'storeDeposit'])->name('deposit.store');
    Route::get('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw.index');
    Route::get('/withdraw/confirm', [WalletController::class, 'withdrawConfirm'])->name('withdraw.confirm');
    Route::get('/withdraw/crypto/network', [WalletController::class, 'cryptoWithdrawNetwork'])->name('withdraw.crypto.network');
    Route::get('/withdraw/crypto/confirm', [WalletController::class, 'cryptoWithdrawConfirm'])->name('withdraw.crypto.confirm');
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
    
    // Chat Routes (Protected - authenticated users only)
    Route::get('/chat/{id}', [ChatController::class, 'getChat'])->name('chat.get');
    Route::post('/chat/{id}/message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
    Route::post('/chat/{id}/mark-read', [ChatController::class, 'markMessagesAsRead'])->name('chat.mark-read');
    Route::get('/chat/{id}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
});

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/password/reset', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Chat Routes (Public - accessible from login page and for guest users)
Route::get('/chat/active', [ChatController::class, 'getActiveChat'])->name('chat.active');
Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
Route::post('/chat/start', [ChatController::class, 'startChat'])->name('chat.start');
Route::post('/chat/{id}/message', [ChatController::class, 'sendMessage'])->name('chat.send-message.public');
Route::get('/chat/{id}/messages', [ChatController::class, 'getMessages'])->name('chat.messages.public');
Route::post('/chat/{id}/mark-read', [ChatController::class, 'markMessagesAsRead'])->name('chat.mark-read.public');

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
    Route::get('/user-growth-data', [HomeController::class, 'getUserGrowthData'])->name('admin.user-growth-data');
    Route::get('/investment-performance-data', [HomeController::class, 'getInvestmentPerformanceData'])->name('admin.investment-performance-data');
    Route::get('/earnings-breakdown-data', [HomeController::class, 'getEarningsBreakdownData'])->name('admin.earnings-breakdown-data');
    Route::get('/transaction-activity-data', [HomeController::class, 'getTransactionActivityData'])->name('admin.transaction-activity-data');
    Route::get('/pending-counts', [HomeController::class, 'getPendingCounts'])->name('admin.pending-counts');
    Route::get('/notifications', [HomeController::class, 'getAdminNotifications'])->name('admin.notifications');

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

    // Admin Crypto Wallet Routes
    Route::prefix('crypto-wallet')->name('admin.crypto-wallet.')->group(function () {
        Route::get('/', [CryptoWalletController::class, 'index'])->name('index');
        Route::get('/create', [CryptoWalletController::class, 'create'])->name('create');
        Route::post('/', [CryptoWalletController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CryptoWalletController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CryptoWalletController::class, 'update'])->name('update');
        Route::delete('/{id}', [CryptoWalletController::class, 'destroy'])->name('destroy');
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

    // Admin Chat Routes
    Route::prefix('chats')->name('admin.chats.')->group(function () {
        Route::get('/', [AdminChatController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminChatController::class, 'show'])->name('show');
        Route::get('/{id}/data', [AdminChatController::class, 'getChat'])->name('get');
        Route::post('/{id}/assign', [AdminChatController::class, 'assignChat'])->name('assign');
        Route::post('/{id}/message', [AdminChatController::class, 'sendMessage'])->name('send-message');
        Route::post('/{id}/close', [AdminChatController::class, 'closeChat'])->name('close');
        Route::get('/unread-count', [AdminChatController::class, 'getUnreadCount'])->name('unread-count');
    });
});
*/

// Database Seeder Route (for production use)
// Access: /run-seeders?token=YOUR_SECRET_TOKEN
Route::get('/run-seeders', function () {
    $token = request()->query('token');
    $secretToken = config('seeder.secret_token');

    // Check if token is provided
    if (empty($token)) {
        return response()->json([
            'error' => 'Unauthorized. Token is required.',
            'message' => 'Please provide a token in the query string: /run-seeders?token=YOUR_SECRET_TOKEN',
        ], 401);
    }

    // Check if token matches
    if ($token !== $secretToken) {
        return response()->json([
            'error' => 'Unauthorized. Invalid token.',
            'message' => 'The provided token does not match the configured secret token.',
        ], 401);
    }

    try {
        // Run all seeders
        Artisan::call('db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => true,
        ]);

        $output = Artisan::output();

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
    $results = [];
    $errors = [];
    
    try {
        // Fix storage permissions first
        $storagePath = storage_path();
        $logsPath = storage_path('logs');
        $cachePath = base_path('bootstrap/cache');
        
        // Ensure directories exist
        if (!is_dir($logsPath)) {
            @mkdir($logsPath, 0755, true);
        }
        if (!is_dir($cachePath)) {
            @mkdir($cachePath, 0755, true);
        }
        
        // Try to set permissions (may fail on some servers, but continue anyway)
        @chmod($logsPath, 0755);
        @chmod($cachePath, 0755);
        @chmod($storagePath, 0755);
        
        // Clear bootstrap cache first (this might fix trait conflicts)
        try {
            $files = glob($cachePath . '/*.php');
            if ($files) {
                foreach ($files as $file) {
                    @unlink($file);
                }
            }
            $results[] = 'Bootstrap cache cleared';
        } catch (\Exception $e) {
            $errors[] = 'Bootstrap cache clear: ' . $e->getMessage();
        }
        
        // Run optimization commands with error handling
        $commands = [
            'optimize:clear' => 'Optimize clear',
            'cache:clear' => 'Cache clear',
            'config:clear' => 'Config clear',
            'route:clear' => 'Route clear',
            'view:clear' => 'View clear',
        ];
        
        foreach ($commands as $command => $label) {
            try {
                Artisan::call($command);
                $results[] = $label . ' completed';
            } catch (\Exception $e) {
                $errors[] = $label . ' failed: ' . $e->getMessage();
            }
        }
        
        // Cache commands
        $cacheCommands = [
            'config:cache' => 'Config cache',
            'route:cache' => 'Route cache',
            'view:cache' => 'View cache',
        ];
        
        foreach ($cacheCommands as $command => $label) {
            try {
                Artisan::call($command);
                $results[] = $label . ' completed';
            } catch (\Exception $e) {
                $errors[] = $label . ' failed: ' . $e->getMessage();
            }
        }
        
        // Run optimize command last
        try {
            Artisan::call('optimize');
            $results[] = 'Optimize completed';
        } catch (\Exception $e) {
            $errors[] = 'Optimize failed: ' . $e->getMessage();
        }
        
        // Return response
        if (empty($errors)) {
            return response()->json([
                'success' => true,
                'message' => 'Application optimized and caches cleared successfully!',
                'results' => $results,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Optimization completed with some errors',
                'results' => $results,
                'errors' => $errors,
            ], 200);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Optimization failed: ' . $e->getMessage(),
            'results' => $results,
            'errors' => array_merge($errors, [$e->getMessage()]),
        ], 500);
    }
});

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return response()->json(['message' => 'Migration successful'], 200);
});

Route::get('/migrate/rollback', function () {
    Artisan::call('migrate:rollback');
    return response()->json(['message' => 'Migration rollback successfully'], 200);
});
