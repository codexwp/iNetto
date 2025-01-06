<?php
namespace App\Services\Gateway\Sms;

use App\Contracts\SmsGatewayInterface;

class BulkSMSBD implements SmsGatewayInterface
{
    private $error = '';
    private $api_url = "http://bulksmsbd.net/api/smsapi";
    private $api_key = null;
    private $sender_id = null;
    private $is_active = false;

    public function __construct($credentials)
    {
        $this->api_key = $credentials['api_key'] ?? null;
        $this->sender_id = $credentials['sender_id'] ?? null;
        $this->is_active = ($credentials['is_active'] ?? "false") == "true";
    }

    public function sendSms(string|array $to, string $message): bool
    {
        if(!$this->is_active)
        {
            $this->error = 'SMS gateway (BulkSMSBD) is not active';
            return false;
        }

        if(is_array($to)){
            foreach ($to as $index => $no) {
                $to[$index] = "88$no";
            }
            $number = implode(',', $to);
        } else {
            $number = "88$to";
        }

        $data = [
            "api_key" => $this->api_key,
            "senderid" => $this->sender_id,
            "number" => $number,
            "message" => $message
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_obj = json_decode($response, true);
        $response_code = $response_obj['response_code'];
        if($response_code == 202) {
            return true;
        } else {
            $this->error = "Code : $response_code, message :" .$response_obj['error_message'];
            return false;
        }
    }

    public function getBalance()
    {
        if($this->api_key == null)
        {
            $this->error = "SMS gateway (BulkSMSBD) is not configured";
            return 'error';
        }

        $data = [
            "api_key" => $this->api_key
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bulksmsbd.net/api/getBalanceApi');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_obj = json_decode($response, true);
        $response_code = $response_obj['response_code'];
        if($response_code == 202) {
            return $response_obj['balance'];
        } else {
            $this->error = "Code : $response_code, message :" .$response_obj['error_message'];
            return false;
        }
    }

    public function errorMessage(): string
    {
        return $this->error;
    }

}
