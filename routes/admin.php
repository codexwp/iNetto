<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TariffController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileController;

Route::middleware(['is_installed'])->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::middleware(['auth:admin'])->group(function () {
        Route::redirect('/', '/admin/dashboard')->name('home');
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

            Route::get('/csv/manage', 'csvManage')->name('csv_manage');
            Route::get('/csv/download', 'csvDownload')->name('csv_download');
            Route::post('/csv/upload', 'csvUpload')->name('csv_upload');

            //APIs
            Route::middleware('force_ajax')->group(function () {
                Route::get('/{user}/fetch', 'fetchDetails')->name('fetch_detail');
                Route::post('/{user}/update-api/{action}', 'updateApi')->name('api.update_api');
                Route::post('/{user}/others-api/{action}', 'othersApi')->name('api.others_api');
                Route::get('/{user}/server-pppoe-status', 'serverPppoeStatus')->name('api.server_pppoe_status');
            });

        });

        Route::prefix('sellers')->name('seller.')->controller(SellerController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'showCreateForm')->name('create');
            Route::post('/create', 'create');
            Route::get('/{seller}/details', 'details')->name('detail');
            Route::post('/{seller}/delete', 'destroy')->name('delete');

            //APIs
            Route::middleware('force_ajax')->group(function () {
                Route::get('/{seller}/fetch', 'fetchDetails')->name('fetch_detail');
                Route::post('/{seller}/update-api/{action}', 'updateApi')->name('api.update_api');
                Route::get('/{seller}/packages', 'fetchPackages')->name('api.package');
                Route::get('/{seller}/users', 'fetchUsers')->name('api.user');
                Route::post('/{seller}/users/transfer', 'usersTransfer')->name('api.user');
            });
        });

        Route::prefix('servers')->name('server.')->controller(ServerController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store');
            Route::get('/test', 'test')->name('test');
            Route::get('/profiles', 'profiles')->name('profile');
            Route::post('/profiles', 'storeProfiles');
            Route::get('/profiles/download', 'downloadProfiles')->name('profile.download');
            Route::get('/clients', 'clients')->name('client');
            Route::get('/clients/live', 'clientLive')->name('client_live');
            Route::post('/clients/status', 'clientStatus')->name('client_status');
        });

        Route::prefix('packages')->name('package.')->controller(PackageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/sort', 'sort')->name('sort');
            Route::get('/create', 'showCreateForm')->name('create');
            Route::post('/create', 'create');
            Route::get('/{package}/update', 'showUpdateForm')->name('update');
            Route::post('/{package}/update', 'update');
            Route::post('/{package}/delete', 'destroy')->name('delete');
        });

        Route::prefix('tariffs')->name('tariff.')->controller(TariffController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'showCreateForm')->name('create');
            Route::post('/create', 'create');
            Route::post('/update', 'update')->name('update');
            Route::post('/{tariff}/delete', 'destroy')->name('delete');
        });

        Route::prefix('settings')->name('setting.')->controller(SettingController::class)->group(function () {
            Route::get('/system', 'system')->name('system');
            Route::get('/sms_gateway', 'smsGateway')->name('sms_gateway');
            Route::get('/payment_gateway', 'paymentGateway')->name('payment_gateway');

            //APIs
            Route::middleware('force_ajax')->group(function () {
                Route::get('/system/data', 'systemData')->name('system_data');
                Route::get('/sms_gateway/data', 'smsGatewayData')->name('sms_gateway_data');
                Route::get('/payment_gateway/data', 'paymentGatewayData')->name('payment_gateway_data');
                Route::get('/send_sms/data', 'sendSmsData')->name('send_sms_data');

                Route::post('/update-api/{prefix}/{action}', 'updateApi')->name('api.update_api');
            });
        });

        Route::prefix('sms')->name('sms.')->controller(SmsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/send', 'showSendFrom')->name('send');

            //APIs
            Route::middleware('force_ajax')->group(function () {
                Route::post('/send', 'send');
                Route::get('/balance', 'balance')->name('balance');
            });

        });

        Route::prefix('payments')->name('payment.')->controller(PaymentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/bulk-payment', 'showBulkPaymentForm')->name('bulk_payment');

            //APIs
            Route::middleware('force_ajax')->group(function () {
                Route::get('/{payment}/fetch', 'fetchPayment')->name('fetch_payment');
                Route::post('/{payment}/update', 'updatePayment')->name('update_payment');
                Route::post('/pay-bill', 'payBill')->name('pay_bill');
                Route::post('/fund-transfer', 'fundTransfer')->name('fund_transfer');

                Route::post('/bulk-payment-data', 'fetchBulkPaymentData')->name('bulk_payment_data');
                Route::post('/bulk-payment-process', 'bulkPaymentProcess')->name('bulk_payment_process');
                Route::post('/grace-payment', 'gracePayment')->name('grace_payment');
            });
        });


    });

});
