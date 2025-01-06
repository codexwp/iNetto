<?php

namespace App\Http\Controllers\Seller;

use App\Contracts\SmsGatewayInterface;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Seller;
use App\Models\User;
use App\Services\NotifyService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        $seller = auth('seller')->user();
        $packages = $seller->getPackagesAndDetails(1);
        $params = $request->only(['user_id', 'package_id', 'trx_id', 'status', 'type', 'from_date','to_date','trx_id']);
        foreach ($params as $k => $p) {
            if($p !== '0' && empty($p)) { unset($params[$k]); }
        }
        $params['seller_id'] = $seller->id;
        $payments = Payment::getByConditions($params, true, 50);
        return view('seller.pages.payment.index', compact('packages',  'payments'));
    }

    public function payBill(Request $request, SmsGatewayInterface $smsGateway)
    {
        try{
            DB::beginTransaction();
            $package_id = $request->package_id;
            $user_id = $request->user_id;
            $pay_by = 'seller';
            $note = $request->note;
            if(empty($package_id) || empty($user_id)) {
                throw new \Exception('Package is empty');
            }
            $payment_id = PaymentService::billPayBySellerOrAdmin($user_id, $package_id, $pay_by, $note);
            DB::commit();

            $user = User::find($user_id);
            (new NotifyService($smsGateway, $user, $payment_id))->sendBillPaidNotification();

            return $this->successResponse('Payment success');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function showBulkPaymentForm(Request $request)
    {
        if(empty($request->post('user_packages'))) {
            abort(404);
        }
        $user_packages = json_decode($request->post('user_packages'));
        if(!$user_packages) {
            abort(404);
        }

        return view('seller.pages.payment.bulk_payment', compact('user_packages'));
    }

    public function fetchBulkPaymentData(Request $request)
    {
        if(empty($request->post('user_packages'))) {
            abort(404);
        }
        $user_packages = json_decode($request->post('user_packages'));
        if(!$user_packages) {
            abort(404);
        }
        try{
            $user_ids = [];
            $uid_pids = [];
            foreach($user_packages as $user_package) {
                $user_ids[] = $user_package->uid;
                $uid_pids[$user_package->uid] = $user_package->pid;
            }
            $users = User::getByUserIds($user_ids);
            $seller_users = [];
            foreach($users as $user) {
                $seller = $user->seller;
                $seller_packages = $seller->getPackagesAndDetails(true);
                $seller_users[$user->seller_id]['packages'] = $seller_packages;
                $seller_users[$user->seller_id]['balance']  = $seller->balance;
                $seller_users[$user->seller_id]['info']  = $seller;
                $user_data = [
                    'info' => $user,
                ];
                $duration = [
                    'start_at' => 'NA',
                    'expire_at' => 'NA',
                ];
                $price = 0;
                $cost = 0;
                $pid = 0;
                foreach($seller_packages as $package) {
                    if($package['id'] === intval($uid_pids[$user->id])) {
                        $duration = PaymentService::prepareExpireDate($user, $user->package);
                        $price = $package['price'];
                        $cost = $package['cost'];
                        $pid = $package['id'];
                        break;
                    }
                }
                $user_data['duration'] = $duration;
                $user_data['price'] = $price;
                $user_data['cost'] = $cost;
                $user_data['pid'] = $pid;
                $seller_users[$user->seller_id]['users'][]  = $user_data;
            }

            return $this->successResponse('Success', $seller_users);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function bulkPaymentProcess(Request $request, SmsGatewayInterface $smsGateway)
    {
        $user_packages = $request->post('user_packages');
        if(!$user_packages) {
            abort(404, 'Invalid Request');
        }

        try{
            foreach($user_packages as $user_package) {
                DB::beginTransaction();
                $payment_id = PaymentService::billPayBySellerOrAdmin($user_package['uid'], $user_package['pid']);
                $user = User::find($user_package['uid']);
                $user->is_expire_notified = 0;
                $user->save();
                (new NotifyService($smsGateway, $user, $payment_id))->sendBillPaidNotification();
                DB::commit();
            }
            return $this->successResponse('Success');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function gracePayment(Request $request)
    {
        try{
            DB::beginTransaction();
            $grace_period = intval(config('settings.system_general.grace_period', 3));
            $user_id = $request->user_id;
            if(empty($user_id)) {
                throw new \Exception('User id is empty');
            }
            $user = User::find($user_id);

            if(empty($user->grace_at))
            {
                $user->expire_at  = $user->expire_at ? Carbon::createFromFormat('Y-m-d', $user->expire_at)->addDays($grace_period) : '';
                if($user->expire_at) {
                    $user->is_active_client = 1;
                    $user->grace_at = now();
                    $user->save();
                } else {
                    throw new \Exception('Grace is not possible');
                }
            } else {
                throw new \Exception('User is already graced');
            }

            DB::commit();
            return $this->successResponse('User has been graced');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }


}
