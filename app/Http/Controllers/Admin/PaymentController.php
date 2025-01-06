<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\SmsGatewayInterface;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Seller;
use App\Models\TariffPackage;
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
        $packages = Package::getAll(1, 'asc');
        $sellers = Seller::getAll('asc');
        $users = User::getAll('asc');
        $params = $request->only(['user_id', 'package_id', 'seller_id', 'trx_id', 'status', 'type', 'from_date','to_date','trx_id']);
        foreach ($params as $k => $p) {
            if($p !== '0' && empty($p)) { unset($params[$k]); }
        }
        $payments = Payment::getByConditions($params, true, 50);
        return view('admin.pages.payment.index', compact('packages', 'sellers', 'users', 'payments'));
    }

    public function fetchPayment(Payment $payment)
    {
        try{
            return $this->successResponse('Success', [
                'payment' => $payment,
                'seller' => $payment->seller?->name,
                'user'   => $payment->user?->name,
                'package'=> $payment->package?->name,
            ]);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function updatePayment(Payment $payment, Request $request)
    {
        try{
            if(empty($request->status) || !in_array($request->status, Payment::STATUS_LIST)) {
                throw new \Exception('Payment status is invalid.');
            }
            if(($payment->status == Payment::STATUS_COMPLETED && $request->status != Payment::STATUS_COMPLETED)
                || ($payment->status == Payment::STATUS_CANCELLED && $request->status != Payment::STATUS_CANCELLED)) {
                throw new \Exception('Unable to update completed/cancelled payment.');
            }

            $payment->status = $request->status;
            $payment->note = $request->note;
            $payment->save();

            return $this->successResponse('Success');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function payBill(Request $request, SmsGatewayInterface $smsGateway)
    {
        try{
            DB::beginTransaction();
            $package_id = $request->package_id;
            $user_id = $request->user_id;
            $pay_by = $request->pay_by;
            $note = $request->note;
            $is_deposit = $request->is_deposit ?? false;
            if(empty($package_id) || empty($user_id) || empty($pay_by)) {
                throw new \Exception('Package is empty');
            }
            $payment_id = PaymentService::billPayBySellerOrAdmin($user_id, $package_id, $pay_by, $note, $is_deposit);
            DB::commit();

            $user = User::find($user_id);
            $user->is_expire_notified = 0;
            $user->save();
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

        return view('admin.pages.payment.bulk_payment', compact('user_packages'));
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


    public function fundTransfer(Request $request)
    {
        try{
            $type = $request->type;
            $amount = $request->amount;
            $seller_id = $request->seller_id;

            if(empty($type) || empty($seller_id) || empty($amount)) {
                throw new \Exception('Type/Amount is empty');
            }
            $seller = Seller::find($seller_id);

            PaymentService::fundTransferByAdmin($seller, $type, $amount, $request->note);
            return $this->successResponse('Payment success');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
