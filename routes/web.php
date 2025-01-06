<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\GuestController;



Route::middleware(['is_installed'])->group(function () {

    Auth::routes();

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

        Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::post('/update', 'update')->name('update');
            Route::get('/change-password', 'showChangePasswordForm')->name('change_password');
            Route::post('/change-password', 'changePassword')->name('change_password');
        });

        Route::prefix('packages')->name('package.')->controller(PackageController::class)->group(function() {
            Route::get('/', 'index')->name('index');
        });

        Route::prefix('payments')->name('payment.')->controller(PaymentController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/bill-pay/{package?}', 'showBillPayForm')->name('bill_pay');

            Route::post('/create/{gateway}', 'createPayment')->name('create_payment');
            Route::get('/callback/{gateway}', 'callbackPayment')->name('callback');
            Route::get('/status', 'showStatus')->name('status');
        });

        Route::prefix('/supports')->name('support.')->controller(SupportController::class)->group(function() {
            Route::get('/', 'index')->name('index');
        });

    });

    Route::prefix('common')->name('common.')->controller(CommonController::class)->group(function() {

        //APIs
        Route::middleware('force_ajax')->group(function () {
            Route::get('/internet-speed/{user}/fetch', 'fetchInternetSpeed')->name('internet_speed');
            Route::post('/execute-cron', 'executeCron')->name('execute_cron');
            Route::get('/new-expire/{user}/{package}', 'getNewExpire')->name('new_expire');
        });

    });

    //Guest Routes
    Route::name('guest.')->controller(GuestController::class)->group(function() {

        Route::get('/', 'index')->name('index');

    });

});

//Install Routes
Route::prefix('install')->controller(GuestController::class)->group(function() {
    Route::get('/', 'showInstallForm')->name('install');
    Route::post('/', 'install');
});


