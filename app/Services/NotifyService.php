<?php


namespace App\Services;


use App\Contracts\SmsGatewayInterface;
use App\Models\Config;
use App\Models\Payment;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyService
{
    private $smsGateway;
    private $user;
    private $data = [];
    private $autosms_config;
    private $is_active;

    public function __construct(SmsGatewayInterface $smsGateway, User $user, int $payment_id = null, $autosms_config = null)
    {
        $this->smsGateway = $smsGateway;
        $this->user = $user;
        $data = $user->toArray();
        if($payment_id) {
            $payment = Payment::find($payment_id);
            $p_data = $payment->toArray();
            unset($p_data['id']);
            $data = array_merge($data, $p_data);
        }
        $package = $user->package;
        $data['currency'] = config('settings.system_general.currency_symbol', '$');
        $data['package_price'] = $package->price;
        $data['package'] = $package->name;
        $data['user_id'] = $user->id;

        $this->data = $data;
        $this->autosms_config = $autosms_config ?? Config::get('system_autosms');
        $this->is_active = config('settings.sms_gateway_default_gateway.is_active', 'false');
    }

    private function replaceShortcodes(string $message)
    {
        foreach ($this->data as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }


    public function sendBillPaidNotification()
    {
        try {
            if ($this->is_active =='true' && $this->autosms_config) {
                $after_billpay_active = ($this->autosms_config['after_billpay_active'] ?? "false") == "true";
                if ($after_billpay_active == 'true') {
                    $message = $this->autosms_config['after_billpay_message'];
                    $message = $this->replaceShortcodes($message);
                    if (($mobile = $this->user->mobile) && $this->user->seller->is_active_user_sms) {
                        $result = $this->smsGateway->sendSms($mobile, $message);
                        Sms::create([
                            'user_id' => $this->user->id,
                            'mobile' => $mobile,
                            'message' => $message,
                            'status' => $result,
                            'reason' => $this->smsGateway->errorMessage() ?? null
                        ]);
                    }

                    if ($email = $this->user->email) {
                        Mail::send('email.default', ['body' => $message], function ($message) use ($email) {
                            $message->to($email)
                                ->subject('WiFi bill paid successfully');
                        });
                    }
                }
            }
        } catch (\Exception $e) {}

    }

    public function sendExpiredNotification()
    {
        try {
            if($this->is_active =='true' && $this->autosms_config)
            {
                $after_expired_active = ($this->autosms_config['after_expired_active'] ?? "false") == "true";
                if($after_expired_active == 'true')
                {
                    $message = $this->autosms_config['after_expired_message'];
                    $message = $this->replaceShortcodes($message);
                    if(($mobile = $this->user->mobile) && $this->user->seller->is_active_user_sms)
                    {
                        $result = $this->smsGateway->sendSms($mobile, $message);
                        Sms::create([
                            'user_id' => $this->user->id,
                            'mobile'  => $mobile,
                            'message' => $message,
                            'status'  => $result,
                            'reason'  => $this->smsGateway->errorMessage() ?? null
                        ]);
                    }

                    if($email = $this->user->email)
                    {
                        Mail::send('email.default', ['body' => $message], function ($message) use ($email) {
                            $message->to($email)
                                ->subject('WiFi has been stopped!!');
                        });
                    }
                }
            }
        } catch (\Exception $e) {}

    }

    public function sendRemainderNotification()
    {

        try {
            if($this->is_active =='true' && $this->autosms_config)
            {

                $before_expire_active = ($this->autosms_config['before_expire_active'] ?? "false") == "true";
                if($before_expire_active == 'true')
                {
                    $message = $this->autosms_config['before_expire_message'];
                    $message = $this->replaceShortcodes($message);
                    if(($mobile = $this->user->mobile) && $this->user->seller->is_active_user_sms)
                    {
                        $result = $this->smsGateway->sendSms($mobile, $message);
                        Sms::create([
                            'user_id' => $this->user->id,
                            'mobile'  => $mobile,
                            'message' => $message,
                            'status'  => $result,
                            'reason'  => $this->smsGateway->errorMessage() ?? null
                        ]);
                    }

                    if($email = $this->user->email)
                    {
                        Mail::send('email.default', ['body' => $message], function ($message) use ($email) {
                            $message->to($email)
                                ->subject('WiFi will expire soon!!');
                        });
                    }
                }
            }
        } catch (\Exception $e) {Log::error($e->getMessage());}
    }

}
