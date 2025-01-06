<?php

namespace App\Providers;

use App\Contracts\SmsGatewayInterface;
use App\Models\Config;
use App\Models\User;
use App\Observers\UserObserver;
use App\Services\Gateway\Sms\BulkSMSBD;
use App\Services\Gateway\Sms\Twilio;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\Payment;
use App\Observers\PaymentObserver;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function register(): void
    {

    }

    public function boot(): void
    {
        try {
            // Check if .env file exists
            if (!File::exists(base_path('.env'))) {
                // Copy .env.example to .env
                File::copy(base_path('.env.example'), base_path('.env'));

                // Generate application key
                Artisan::call('key:generate');
            }

             //Cache Default Settings
            $settings = Cache::remember("settings", 3600, function () {
                $settings = Config::get(["system_general", "sms_gateway_default_gateway", "system_smtp"]);
                $sms_gateway_default = $settings['sms_gateway_default_gateway'] ?? ['name' => 'bulksmsbd'];
                $settings['sms_gateway_default_gateway'] = $sms_gateway_default;
                $settings['sms_gateway_credentials'] = Config::get("sms_gateway_{$sms_gateway_default['name']}");
                return $settings;
            });

            if(isset($settings['system_smtp'])) {
                $smtp_config = $settings['system_smtp'];
                $smtp = config('mail.mailers.smtp');
                $smtp_from = config('mail.from');
                $smtp['host'] = $smtp_config['host'];
                $smtp['port'] = $smtp_config['port'];
                $smtp['username'] = $smtp_config['username'];
                $smtp['password'] = $smtp_config['password'];
                $smtp['encryption'] = $smtp_config['encryption'] ?? null;
                $smtp_from['address'] = $smtp_config['from_address'];
                $smtp_from['name'] = $smtp_config['from_name'];
                config(['mail.mailers.smtp' => $smtp]);
                config(['mail.from' => $smtp_from]);
                unset($settings['system_smtp']);
            }

            config(['settings' => $settings]);
            $tz = config('settings.system_general.time_zone', 'UTC');
            config(['app.timezone' => $tz]);
            date_default_timezone_set($tz);

            //Binding Active SMS Gateway
            $this->app->bind(SmsGatewayInterface::class, function () {
                $gateway = config('settings.sms_gateway_default_gateway.name');
                $credentials = config('settings.sms_gateway_credentials');

                switch ($gateway) {
                    case "bulksmsbd":
                        return new BulkSMSBD($credentials);
                    case "twilio":
                        return new Twilio($credentials);
                }
            });
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        //Payment create/update check observer
        Payment::observe(PaymentObserver::class);
        //User create/update check observer
        User::observe(UserObserver::class);
    }
}
