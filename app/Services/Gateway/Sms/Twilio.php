<?php


namespace App\Services\Gateway\Sms;
use App\Contracts\SmsGatewayInterface;
use Twilio\Rest\Client;

class Twilio implements SmsGatewayInterface
{
    protected $client;
    protected $from;
    private $country_code;
    private $error = '';
    private $is_active = false;

    public function __construct($credentials)
    {
        $this->client = new Client($credentials['sid'], $credentials['token']);
        $this->from = $credentials['from'];
        $this->country_code = config('settings.system_general.country_iso');
        $this->is_active = ($credentials['is_active'] ?? "false") == "true";
    }

    public function sendSms(string|array $to, string $message)
    {
        if(!$this->is_active)
        {
            $this->error = 'SMS gateway (Twilio) is not active';
            return false;
        }

        try {
            if(is_array($to)){
                foreach ($to as $no) {
                    $this->client->messages->create($no, [
                        'from' => $this->from,
                        'body' => $message,
                    ]);
                }
            } else {
                $this->client->messages->create($to, [
                    'from' => $this->from,
                    'body' => $message,
                ]);
            }
            return true;
        } catch (\Exception $e) {
            $this->error = "Twilio SMS Error: " . $e->getMessage();
            return false;
        }
    }

    public function getBalance()
    {
        // TODO: Implement getBalance() method.
    }

    public function errorMessage(): string
    {
        return $this->error;
    }
}
