<?php

namespace App\Observers;

use App\Contracts\SmsGatewayInterface;
use App\Models\Config;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Sms;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        if($payment->status != Payment::STATUS_COMPLETED) {
            return;
        }

        if($payment->type == Payment::TYPE_BILL)
        {
            $user = $payment->user;
            $package = Package::find($payment->package_id);
            $duration = PaymentService::prepareExpireDate($user, $package);
            $start_dt_tmp = $duration['start_at'];
            $expire_dt    = $duration['expire_at'];

            $seller = $user->seller;
            if($payment->bill_pay_by == Payment::USER_TYPE_SELLER) {
                $payment->seller_prev_bal = $seller->balance;
                $seller->deductBalance($payment->cost);
                $payment->seller_new_bal = $seller->balance;
            }
            elseif($payment->bill_pay_by == Payment::USER_TYPE_USER){
                $commission = $payment->amount - $payment->cost;
                if($commission) {
                    $seller->payments()->create([
                        'type' => Payment::TYPE_COMMISSION,
                        'amount' => $commission,
                        'status' => Payment::STATUS_COMPLETED,
                        'action_by' => $payment->action_by,
                        'action_by_id' => $payment->action_by_id,
                        'gateway' => Payment::GW_MANUAL,
                        'note' => 'Bill Commission - ' . $payment->id
                    ]);
                }
            }

            $user->expire_at = $expire_dt;
            $user->package_id = $package->id;
            $user->is_active_client = 1;
            $user->grace_at = null;
            $user->save();

            $payment->user_start_at = $start_dt_tmp;
            $payment->user_expire_at = $expire_dt;
            $payment->save();

        }
        else if($payment->type == Payment::TYPE_DEPOSIT || $payment->type == Payment::TYPE_COMMISSION) {
            $seller = $payment->seller;
            $payment->seller_prev_bal = $seller->balance;
            $seller->addBalance($payment->amount);
            $payment->seller_new_bal = $seller->balance;
            $payment->save();
        }
        else if($payment->type == Payment::TYPE_RETURN || $payment->type == Payment::TYPE_WITHDRAW) {
            $seller = $payment->seller;
            $payment->seller_prev_bal = $seller->balance;
            $seller->deductBalance($payment->amount);
            $payment->seller_new_bal = $seller->balance;
            $payment->save();
        }
        else {
            // For refund is remaining for user
        }

    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updating(Payment $payment): void
    {
        if($payment->isDirty('status') && $payment->status === Payment::STATUS_COMPLETED && $payment->type === Payment::TYPE_BILL)
        {
            if($payment->user_expire_at) {
                return;
            }

            $user = $payment->user;
            $package = Package::find($payment->package_id);

            $duration = PaymentService::prepareExpireDate($user, $package);
            $start_dt_tmp = $duration['start_at'];
            $expire_dt = $duration['expire_at'];

            $seller = $user->seller;
            if ($payment->bill_pay_by == Payment::USER_TYPE_USER) {
                $commission = $payment->amount - $payment->cost;
                if ($commission) {
                    $seller->payments()->create([
                        'type' => Payment::TYPE_COMMISSION,
                        'amount' => $commission,
                        'status' => Payment::STATUS_COMPLETED,
                        'action_by' => $payment->action_by,
                        'action_by_id' => $payment->action_by_id,
                        'gateway' => Payment::GW_MANUAL,
                        'note' => 'Bill Commission - ' . $payment->id
                    ]);
                }
            }

            $user->expire_at = $expire_dt;
            $user->package_id = $package->id;
            $user->is_active_client = 1;
            $user->grace_at = null;
            $user->save();

            $payment->user_start_at = $start_dt_tmp;
            $payment->user_expire_at = $expire_dt;
            $payment->save();
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
