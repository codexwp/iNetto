<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    private $action;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rule = [];

        switch ($this->action) {
            case 'system_general':
                $rule = [
                    'title' => ['required', 'string', 'max:255'],
                    'logo_text' => ['nullable', 'string', 'max:255'],
                    'currency_symbol' => ['required', 'string'],
                    'currency_code' => ['required', 'string'],
                    'logo' => ['nullable', 'required_if:logo_path,null', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048', ],
                    'logo_path' => ['nullable', 'string'],
                    'grace_period' => ['required', 'integer'],
                    'contact_no' => ['required', 'string'],
                    'country_iso' => ['required', 'string'],
                    'time_zone' => ['required', 'string'],
                    'copyright' => ['required', 'string'],
                    'contact_email' => ['required', 'email'],
                    'location' => ['required', 'string'],
                    'force_https' => ['nullable', 'string'],
                ];
                break;
            case 'system_autosms':
                $rule = [
                    'before_expire_active' => ['nullable', 'string'],
                    'before_expire_days' => ['nullable', 'required_if:before_expire_active,true', 'integer'],
                    'before_expire_message' => ['nullable', 'required_if:before_expire_active,true', 'string'],
                    'after_expired_active' => ['nullable', 'string'],
                    'after_expired_message' => ['nullable', 'required_if:after_expired_active,true', 'string'],
                    'after_billpay_active' => ['nullable', 'string'],
                    'after_billpay_message' => ['nullable', 'required_if:after_billpay_active,true', 'string'],
                ];
                break;
            case 'system_smtp':
                $rule = [
                    'host' => ['required', 'string'],
                    'port' => ['required', 'numeric'],
                    'username' => ['required', 'string'],
                    'password' => ['required', 'string'],
                    'encryption' => ['nullable', 'string'],
                    'from_address' => ['required', 'string'],
                    'from_name' => ['required', 'string'],
                ];
                break;
            case 'send_sms_templates':
                $rule = [
                    '*.name' => ['required', 'max:255'],
                    '*.message' => ['required', 'string'],
                ];
                break;
            case 'sms_gateway_default_gateway':
                $rule = [
                    'name' => ['required', 'string'],
                    'is_active' => ['nullable', 'string'],
                ];
                break;
            case 'sms_gateway_bulksmsbd':
                $rule = [
                    'is_active' => ['nullable', 'in:true,false'],
                    'api_key' => ['required', 'string'],
                    'sender_id' => ['required', 'string'],
                ];
                break;
            case 'sms_gateway_twilio':
                $rule = [
                    'is_active' => ['nullable', 'in:true,false'],
                    'sid' => ['required', 'string'],
                    'token' => ['required', 'string'],
                    'from' => ['required', 'string'],
                ];
                break;
            case 'payment_gateway_bkash':
                $rule = [
                    'is_active' => ['nullable', 'in:true,false'],
                    'app_key' => ['required', 'string'],
                    'app_secret' => ['required', 'string'],
                    'username' => ['required', 'string'],
                    'password' => ['required', 'string'],
                ];
                break;

            case 'payment_gateway_stripe':
                $rule = [
                    'is_active' => ['nullable', 'in:true,false'],
                    'stripe_key' => ['required', 'string'],
                    'stripe_secret' => ['required', 'string'],
                ];
                break;

            case 'payment_gateway_offline':
                $rule = [
                    'is_active' => ['nullable', 'in:true,false'],
                    'message' => ['required', 'string'],
                ];
                break;


            default:
                abort(403, 'Unauthorized action');
        }
        return $rule;
    }

    public function prepareForValidation()
    {
        $this->action = $this->route('prefix') . '_' .$this->route('action');
    }
}
