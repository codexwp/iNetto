<?php


namespace App\Services\Gateway\Payment;
use App\Contracts\PaymentGatewayInterface;
use App\Models\Config;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;

class StripePaymentGatway implements PaymentGatewayInterface
{
    public function __construct()
    {
        $stripe = Config::get('payment_gateway_stripe');
        Stripe::setApiKey($stripe['stripe_secret']);
    }

    public function create(array $data)
    {
        $cents_not_supported_countries = [
            'BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','UGX','VND','VUV','XAF','XOF','XPF',
        ];
        $unit_amount = $data['amount'];
        $currency = $data['currency'] ?? 'USD';
        if(!in_array($currency, $cents_not_supported_countries)) {
            $unit_amount = $unit_amount * 100;
        }
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $data['product_name'],
                    ],
                    'unit_amount' => $unit_amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.callback', ['gateway' => 'stripe']) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.callback', ['gateway' => 'stripe']),
            'metadata' => [
                'payment_id' => $data['payment_id'],
            ],
        ]);

        return ['payment_url' => $session->url];
    }

    public function verify(Request $request)
    {
        $sessionId = $request->get('session_id');
        if(empty($sessionId)) {
            return false;
        }

        $session = Session::retrieve($sessionId);

        if ($session->payment_status === 'paid') {
            return [
                'payment_id' => $session->metadata->payment_id,
                'trx_id' => $session->payment_intent
            ];
        }

        return false;
    }

}
