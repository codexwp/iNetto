<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\Auth\LoginController;
use App\Http\Controllers\Seller\Auth\ForgotPasswordController;
use App\Http\Controllers\Seller\Auth\ResetPasswordController;

use App\Http\Controllers\Seller\HomeController;
use App\Http\Controllers\Seller\UserController;
use App\Http\Controllers\Seller\PackageController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\ProfileController;

Route::middleware(['is_installed'])->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::middleware(['auth:seller'])->group(function () {
        Route::redirect('/', '/seller/dashboard')->name('home');
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

        Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/update', 'update')->name('update');
            Route::get('/change-password', 'showChangePasswordForm')->name('change_password');
            Route::post('/change-password', 'changePassword')->name('change_password');
        });

        Route::prefix('users')->name('user.')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'showCreateForm')->name('create');
            Route::post('/create', 'create');
            Route::get('/{user}', 'details')->name('detail');

            //APIs
            Route::middleware('force_ajax')->group(function () {
                Route::get('/{user}/fetch', 'fetchDetails')->name('fetch_detail');
                Route::post('/{user}/update-api/{action}', 'updateApi')->name('api.update_api');
                Route::post('/{user}/others-api/{action}', 'othersApi')->name('api.others_api');
                Route::get('/{user}/server-pppoe-status', 'serverPppoeStatus')->name('api.server_pppoe_status');
            });

        });

        Route::prefix('packages')->name('package.')->controller(PackageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::prefix('payments')->name('payment.')->controller(PaymentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/bulk-payment', 'showBulkPaymentForm')->name('bulk_payment');

            //APIs
            Route::middleware('force_ajax')->group(function () {
                Route::post('/pay-bill', 'payBill')->name('pay_bill');
                Route::post('/bulk-payment-data', 'fetchBulkPaymentData')->name('bulk_payment_data');
                Route::post('/bulk-payment-process', 'bulkPaymentProcess')->name('bulk_payment_process');
                Route::post('/grace-payment', 'gracePayment')->name('grace_payment');
            });
        });
    });

});
