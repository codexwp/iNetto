<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\SmsGatewayInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Config;
use App\Models\CronJob;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function system()
    {
        return view('admin.pages.setting.system');
    }

    public function smsGateway()
    {
        return view('admin.pages.setting.sms_gateway');
    }

    public function paymentGateway()
    {
        return view('admin.pages.setting.payment_gateway');
    }

    //APIs
    public function systemData()
    {
        $data = Config::get(['system_general', 'system_smtp', 'system_autosms']);
        $data['cron_jobs'] = CronJob::orderBy('id', 'desc')->take(5)->get();
        return $data;
    }

    public function smsGatewayData()
    {
        return Config::get(['sms_gateway_default_gateway','sms_gateway_bulksmsbd', 'sms_gateway_twilio']);
    }

    public function paymentGatewayData()
    {
        return Config::get(['payment_gateway_bkash', 'payment_gateway_stripe', 'payment_gateway_offline']);
    }

    public function sendSmsData()
    {
        $data = Config::get(['send_sms_templates']);
        $data['sellers'] = Seller::getAll();
        return $data;
    }

    public function updateApi(SettingRequest $request, string $prefix, string $action)
    {
        try{
            $data = $request->validated();
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $data['logo_path'] = $logo->store('images', 'public');
            }
            Config::set("{$prefix}_{$action}", $data);
            Cache::flush();
            return $this->successResponse('Successfully updated');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
