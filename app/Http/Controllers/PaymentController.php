<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Package;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();
        $params = $request->only(['from_date','to_date']);
        foreach ($params as $k => $p) {
            if($p !== '0' && empty($p)) { unset($params[$k]); }
        }
        $params['user_id'] = $user_id;
        $params['status']  = Payment::STATUS_COMPLETED;
        $payments = Payment::getByConditions($params, true, 50);
        return view('pages.payment.index', compact(  'payments'));
    }
    public function showBillPayForm(Package $package = null)
    {
        $gw_arr = ['payment_gateway_bkash', 'payment_gateway_stripe', 'payment_gateway_offline'];
        $payment_gateways = Config::get($gw_arr);
        $gateways = [];
        $offline_message = '';
        foreach ($payment_gateways as $key => $gateway) {
            if($gateway['is_active'] === 'true') {
                $index = array_search($key, $gw_arr);
                $gateways[$index] = [
                    'name' => explode('payment_gateway_', $key)[1],
                    'logo' => asset('assets/img/icons/payment/' . $key . '.png'),
                ];
                if($key === 'payment_gateway_offline') {
                    $offline_message = $gateway['message'];
                }
            }
        }
        $user = auth()->user();
        if($package) {
            $duration = PaymentService::prepareExpireDate($user, $package);
        } else {
            $duration = PaymentService::prepareExpireDate($user, $user->package);
        }
        $data = [
            'user' => $user,
            'package' => $user->package,
            'new_package' => $package,
            'gateways' => $gateways,
            'duration' => $duration,
            'offline_message' => nl2br($offline_message),
        ];

        return view('pages.payment.bill_pay', $data);
    }

    public function createPayment(Request $request, $gateway)
    {
        $user_id = auth()->id();
        $package_id = $request->input('package_id');
        $package = Package::find($package_id);

        try {
            DB::beginTransaction();
            $payment_id = PaymentService::billPayByUser($user_id, $package_id, $gateway);
            $paymentGateway = PaymentService::initializeGateway($gateway);
            $paymentData = [
                'payment_id' => $payment_id,
                'amount' => $package->price,
                'product_name' => $package->name . ' for ' . $package->valid,
                'currency' => config('settings.system_general.currency_code', 'USD'),
                'callback_url' => route('payment.callback', ['gateway' => $gateway]),
            ];

            $paymentResponse = $paymentGateway->create($paymentData);

            DB::commit();

            return $this->successResponse('Success', ['payment_url' => $paymentResponse['payment_url']]);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function callbackPayment(Request $request, $gateway)
    {
        try{
            DB::beginTransaction();
            $paymentGateway = PaymentService::initializeGateway($gateway);
            $response = $paymentGateway->verify($request);
            if(!$response) {
                throw new \Exception('Payment failed');
            }
            $payment = Payment::where($response['payment_id']);
            if($payment->trx_id) {
                throw new \Exception('Invalid request');
            }
            $payment->status = Payment::STATUS_COMPLETED;
            $payment->trx_id = $response['trx_id'];
            $payment->save();
            DB::commit();
            return redirect()->route('payment.status')->with('success', 'Payment successful');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('payment.status')->with('error', $e->getMessage());
        }
    }

    public function showStatus()
    {
        return view('pages.payment.status');
    }
}
