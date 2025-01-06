<?php


namespace App\Services\Gateway\Payment;
use App\Contracts\PaymentGatewayInterface;
use App\Models\Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BkashPaymentGatway implements PaymentGatewayInterface
{
    private $base_url = 'https://checkout.sandbox.bka.sh/v1.2.0-beta';
    private $credentials;

    public function __construct()
    {
        $this->client = new Client();
        $this->credentials = Config::get('payment_gateway_bkash');
    }

    private function getToken()
    {
        $response = $this->client->post("{$this->base_url}/checkout/token/grant", [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'username' => $this->credentials['username'],
                'password' => $this->credentials['password']
            ],
            'json' => [
                'app_key' => $this->credentials['app_key'],
                'app_secret' => $this->credentials['app_secret'],
            ],
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        if(isset($response['status']) && $response['status'] === 'fail') {
            throw new \Exception("BKASH payment gateway returned error: " . $response['msg']);
        }

        return $response['id_token'];
    }

    public function create(array $data)
    {
        $token = $this->getToken();

        $response = $this->client->post("{$this->base_url}/checkout/payment/create", [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'Authorization' => $token,
                'X-APP-Key' => $this->credentials['app_key'],
            ],
            'json' => [
                'payerReference' => $data['payment_id'],
                'callbackURL' => route('payment.callback', ['gateway' => 'bkash']),
                'amount' => $data['amount'],
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => 'inv_' . encrypt_decrypt($data['payment_id']),

            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        if(isset($response['transactionStatus']) && $response['transactionStatus'] === "Initiated") {
            return ['payment_url' => $response['bkashURL']];
        } else {
            throw new \Exception(json_encode($response));
        }
    }

    public function verify(Request $request)
    {
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');
        if($status !== 'success' || empty($paymentId)) {
            return false;
        }

        $token = $this->getToken();
        $response = $this->client->post("{$this->base_url}/checkout/payment/execute/{$paymentId}", [
            'headers' => [
                'Authorization' => $token,
                'Content-Type' => 'application/json',
                'x-app-key' => $this->credentials['app_key']
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        if(isset($response['transactionStatus']) && $response['transactionStatus'] === "Completed") {
            return [
                'payment_id' => $response['payerReference'],
                'trx_id' => $response['trxID']
            ];
        }

        return false;
    }

}
