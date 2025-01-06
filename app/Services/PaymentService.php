<?php


namespace App\Services;


use App\Models\Package;
use App\Models\Payment;
use App\Models\Seller;
use App\Models\User;
use App\Services\Gateway\Payment\BkashPaymentGatway;
use App\Services\Gateway\Payment\Nagad;
use App\Services\Gateway\Payment\StripePaymentGatway;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{

    /**
     * @throws \Exception
     */
    public static function initializeGateway(string $gateway)
    {
        return match ($gateway) {
            'bkash' => new BkashPaymentGatway(),
            'stripe' => new StripePaymentGatway(),
            default => throw new \Exception("Payment gateway not supported."),
        };
    }

    public static function billPayBySellerOrAdmin(int $user_id, int $package_id = null, string $bill_pay_by = 'seller', string $note = null, bool $with_deposit = false)
    {
        try{
            if(!in_array($bill_pay_by, [Payment::USER_TYPE_USER, Payment::USER_TYPE_SELLER])) {
                throw new \Exception('Invalid bill pay by type');
            }
            $user = User::find($user_id);
            $user_seller = $user->seller;

            $package_id = $package_id ?? $user->package_id;
            $tariff_package = $user_seller->tariff->tariffPackage($package_id);

            if(is_null($tariff_package)) {
                throw new \Exception('Package is not active any more');
            }

            $auth_seller = auth('seller')->user();
            $auth_admin = auth('admin')->user();

            if($bill_pay_by == Payment::USER_TYPE_SELLER && $auth_seller && $auth_seller->id != $user_seller->id) {
                throw new \Exception('Unauthorized user');
            }

            $package = $tariff_package->package;
            if($package->serverProfile == null) {
                throw new \Exception('Profile is not exists');
            }

            $package_price = intval($package->price);
            $cost = intval($tariff_package->cost);
            $seller_balance = intval($user_seller->balance);

            if($bill_pay_by == Payment::USER_TYPE_SELLER) {
                if($with_deposit) {
                    $user->seller->payments()->create([
                        'type' => Payment::TYPE_DEPOSIT,
                        'amount' => $cost,
                        'status' => Payment::STATUS_COMPLETED,
                        'action_by' => Payment::USER_TYPE_ADMIN,
                        'action_by_id' => $auth_admin ->id,
                        'gateway' => Payment::GW_MANUAL,
                    ]);
                } else if($seller_balance < $cost) {
                    throw new \Exception('Not enough balance');
                }
            }

            //Create a payment record
            $data = array(
              'seller_id'       => $user_seller->id,
              'type'            => Payment::TYPE_BILL,
              'amount'          => $package_price,
              'cost'            => $cost,
              'bill_pay_by'     => $bill_pay_by,
              'action_by'       => $auth_admin ? Payment::USER_TYPE_ADMIN : Payment::USER_TYPE_SELLER,
              'action_by_id'    => $auth_admin ? $auth_admin->id : $auth_seller->id,
              'status'          => Payment::STATUS_COMPLETED,
              'gateway'         => Payment::GW_MANUAL,
              'note'            => $note,
              'package_id'      => $package_id
            );
            $payment = $user->payments()->create($data);
            return $payment->id;
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    public static function billPayByUser(int $user_id, int $package_id, string $gateway, string $note = null)
    {
        try{
            $user = User::find($user_id);
            $user_seller = $user->seller;
            $tariff_package = $user_seller->tariff->tariffPackage($package_id);

            if(is_null($tariff_package)) {
                throw new \Exception('Package is not active any more');
            }

            $package = $tariff_package->package;
            if($package->serverProfile == null) {
                throw new \Exception('Profile is not exists');
            }

            $package_price = intval($package->price);
            $cost = intval($tariff_package->cost);

            //Create a payment record
            $data = array(
                'seller_id'       => $user_seller->id,
                'type'            => Payment::TYPE_BILL,
                'amount'          => $package_price,
                'cost'            => $cost,
                'bill_pay_by'     => Payment::USER_TYPE_USER,
                'action_by'       => Payment::USER_TYPE_USER,
                'action_by_id'    => $user->id,
                'status'          => Payment::STATUS_PENDING,
                'gateway'         => $gateway,
                'note'            => $note,
                'package_id'      => $package_id
            );
            $payment = $user->payments()->create($data);
            return $payment->id;
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    public static function fundTransferByAdmin(Seller $seller, string $type, int $amount, $note = null)
    {
        try{
            DB::beginTransaction();
            $seller->payments()->create([
                'type' => $type,
                'amount' => $amount,
                'status' => Payment::STATUS_COMPLETED,
                'action_by' => Payment::USER_TYPE_ADMIN,
                'action_by_id' => auth('admin')->id(),
                'gateway' => Payment::GW_MANUAL,
                'note' => $note
            ]);
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public static function prepareExpireDate(User $user, Package $package)
    {
        $is_expired = false;
        if(!is_null($user->expire_at)) {
            $is_expired = Carbon::createFromTimeString($user->expire_at . " 23:59:59")->lessThan(now());
        }

        if($user->grace_at && Carbon::createFromTimeString($user->grace_at)->diff(now())->days < 25) {
            $start_dt = Carbon::createFromTimeString($user->grace_at);
        } else {
            if(is_null($user->expire_at) || $user->package_id != $package->id || $is_expired) {
                $start_dt = now();
            }
            else {
                $start_dt = Carbon::createFromFormat('Y-m-d', $user->expire_at);
            }
        }
        $start_dt_tmp = $start_dt->format('Y-m-d');
        if($package->validity_unit == Package::V_UNIT_MONTH) {
            $expire_dt = $start_dt->addMonths($package->validity);
        } else if($package->validity_unit == Package::V_UNIT_DAY) {
            $expire_dt = $start_dt->addDays($package->validity);
        }

        return [
            'start_at' => $start_dt_tmp,
            'expire_at'=> $expire_dt->format('Y-m-d')
        ];
    }

}
