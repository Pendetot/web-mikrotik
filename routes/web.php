<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminMidtransController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/pay', [InvoiceController::class, 'pay'])->name('pay');
        Route::get('/{invoice}/download', [InvoiceController::class, 'download'])->name('download');
        Route::get('/{invoice}/view', [InvoiceController::class, 'showFull'])->name('view');
    });

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/update-password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/{id}', [SubscriptionController::class, 'show'])->name('show');
        Route::get('/{id}/renew', [SubscriptionController::class, 'renew'])->name('renew');
        Route::post('/{id}/renew', [SubscriptionController::class, 'processRenewal'])->name('process-renewal');
        Route::post('/{id}/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    });

    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/{id}', [PackageController::class, 'show'])->name('show');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/create', [AdminController::class, 'createUser'])->name('create');
        Route::post('/', [AdminController::class, 'storeUser'])->name('store');
        Route::get('/{user}', [AdminController::class, 'showUser'])->name('show');
        Route::get('/{user}/edit', [AdminController::class, 'editUser'])->name('edit');
        Route::put('/{user}', [AdminController::class, 'updateUser'])->name('update');
        Route::patch('/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('toggle-status');
        Route::delete('/{user}', [AdminController::class, 'deleteUser'])->name('destroy');
    });
    
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [AdminPackageController::class, 'index'])->name('index');
        Route::get('/create', [AdminPackageController::class, 'create'])->name('create');
        Route::post('/', [AdminPackageController::class, 'store'])->name('store');
        Route::get('/{package}', [AdminPackageController::class, 'show'])->name('show');
        Route::get('/{package}/edit', [AdminPackageController::class, 'edit'])->name('edit');
        Route::put('/{package}', [AdminPackageController::class, 'update'])->name('update');
        Route::delete('/{package}', [AdminPackageController::class, 'destroy'])->name('destroy');
        Route::patch('/{package}/toggle-status', [AdminPackageController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{package}/toggle-featured', [AdminPackageController::class, 'toggleFeatured'])->name('toggle-featured');
        
        Route::get('/categories', [AdminPackageController::class, 'categories'])->name('categories');
        Route::get('/categories/create', [AdminPackageController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [AdminPackageController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminPackageController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminPackageController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminPackageController::class, 'destroyCategory'])->name('categories.destroy');
    });
    
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/create', [AdminPaymentController::class, 'create'])->name('create');
        Route::post('/', [AdminPaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('edit');
        Route::put('/{payment}', [AdminPaymentController::class, 'update'])->name('update');
        Route::patch('/{payment}/status', [AdminPaymentController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{payment}', [AdminPaymentController::class, 'destroy'])->name('destroy');
        
        Route::get('/pending', [AdminPaymentController::class, 'pending'])->name('pending');
        Route::get('/completed', [AdminPaymentController::class, 'completed'])->name('completed');
        Route::get('/failed', [AdminPaymentController::class, 'failed'])->name('failed');
        Route::get('/reports', [AdminPaymentController::class, 'reports'])->name('reports');
        Route::get('/export', [AdminPaymentController::class, 'export'])->name('export');
        
        Route::post('/bulk-approve', [AdminPaymentController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [AdminPaymentController::class, 'bulkReject'])->name('bulk-reject');
    });

    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [AdminInvoiceController::class, 'index'])->name('index');
        Route::get('/create', [AdminInvoiceController::class, 'create'])->name('create');
        Route::post('/', [AdminInvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [AdminInvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [AdminInvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [AdminInvoiceController::class, 'update'])->name('update');
        Route::patch('/{invoice}/status', [AdminPaymentController::class, 'updateInvoiceStatus'])->name('update-status');
        Route::delete('/{invoice}', [AdminInvoiceController::class, 'destroy'])->name('destroy');
        Route::get('/{invoice}/download', [AdminInvoiceController::class, 'download'])->name('download');
        Route::get('/{invoice}/send', [AdminInvoiceController::class, 'send'])->name('send');
        Route::post('/{invoice}/send', [AdminInvoiceController::class, 'processSend'])->name('process-send');
        
        Route::get('/overdue', [AdminInvoiceController::class, 'overdue'])->name('overdue');
        Route::get('/recurring', [AdminInvoiceController::class, 'recurring'])->name('recurring');
        Route::post('/bulk-send', [AdminInvoiceController::class, 'bulkSend'])->name('bulk-send');
    });

    Route::prefix('midtrans')->name('midtrans.')->group(function () {
        Route::get('/', [AdminMidtransController::class, 'index'])->name('index');
        Route::post('/update', [AdminMidtransController::class, 'update'])->name('update');
        Route::post('/test-connection', [AdminMidtransController::class, 'testConnection'])->name('test-connection');
        Route::get('/webhook-logs', [AdminMidtransController::class, 'webhookLogs'])->name('webhook-logs');
        Route::post('/clear-logs', [AdminMidtransController::class, 'clearLogs'])->name('clear-logs');
    });
    
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
    Route::get('/logs/export', [AdminController::class, 'exportLogs'])->name('logs.export');
    Route::delete('/logs/clear', [AdminController::class, 'clearLogs'])->name('logs.clear');
    
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [AdminController::class, 'generalSettings'])->name('general');
        Route::post('/general', [AdminController::class, 'updateGeneralSettings'])->name('general.update');
        
        Route::get('/payment', [AdminController::class, 'paymentSettings'])->name('payment');
        Route::post('/payment', [AdminController::class, 'updatePaymentSettings'])->name('payment.update');
        Route::post('/payment/test-gateway', [AdminController::class, 'testPaymentGateway'])->name('payment.test-gateway');
        
        Route::get('/notifications', [AdminController::class, 'notificationSettings'])->name('notifications');
        Route::post('/notifications', [AdminController::class, 'updateNotificationSettings'])->name('notifications.update');
        Route::post('/notifications/test', [AdminController::class, 'testNotification'])->name('notifications.test');
        
        Route::get('/system', [AdminController::class, 'systemSettings'])->name('system');
        Route::post('/system', [AdminController::class, 'updateSystemSettings'])->name('system.update');
        Route::post('/system/cache-clear', [AdminController::class, 'clearCache'])->name('system.cache-clear');
        Route::post('/system/maintenance', [AdminController::class, 'toggleMaintenance'])->name('system.maintenance');
    });
    
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard-stats', [AdminController::class, 'getDashboardStats'])->name('dashboard-stats');
        Route::get('/payment-chart-data', [AdminPaymentController::class, 'getChartData'])->name('payment-chart-data');
        Route::get('/user-activity', [AdminController::class, 'getUserActivity'])->name('user-activity');
        Route::get('/system-status', [AdminController::class, 'getSystemStatus'])->name('system-status');
    });
});

Route::fallback(function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return abort(404);
});