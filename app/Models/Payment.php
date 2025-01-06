<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const TYPE_BILL = 'bill';
    const TYPE_COMMISSION = 'commission';
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_RETURN = 'return';
    const TYPE_REFUND = 'refund';
    const TYPE_LIST = [
        self::TYPE_BILL,
        self::TYPE_COMMISSION,
        self::TYPE_DEPOSIT,
        self::TYPE_WITHDRAW,
        self::TYPE_RETURN,
        self::TYPE_REFUND
    ];

    const GW_MANUAL = 'manual';
    const GW_BKASH = 'bkash';
    const GW_STRIPE = 'stripe';
    const GW_LIST = [
        self::GW_MANUAL,
        self::GW_BKASH,
        self::GW_STRIPE
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_HOLD = 'hold';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_LIST = [
        self::STATUS_PENDING,
        self::STATUS_PROCESSING,
        self::STATUS_HOLD,
        self::STATUS_CANCELLED,
        self::STATUS_COMPLETED
    ];

    const USER_TYPE_USER = 'user';
    const USER_TYPE_SELLER = 'seller';
    const USER_TYPE_ADMIN = 'admin';
    const USER_TYPE_LIST = [
        self::USER_TYPE_USER,
        self::USER_TYPE_SELLER,
        self::USER_TYPE_ADMIN
    ];

    public static function getByConditions($params = [], bool $is_paginate = false, int $no_of_rows = 10)
    {
        $query = self::query();
        $from_date = $params['from_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $to_date = $params['to_date'] ?? now()->endOfMonth()->format('Y-m-d');
        unset($params['from_date'], $params['to_date']);

        if(count($params)){
            $query->where($params);
        }
        $query->where('created_at','>=', $from_date)->where('created_at', '<=', $to_date . ' 23:59:59');

        $query->orderBy('id', 'desc');
        $total = $query->sum('amount');
        $records =  $is_paginate ? $query->paginate($no_of_rows)->appends($params) : $query->get();
        return ['total_amount' => $total, 'records' => $records];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
