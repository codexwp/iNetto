<?php

namespace App\Models;

use App\Notifications\Seller\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public static function getAll(string $order_by = 'desc')
    {
        return self::orderBy('created_at', $order_by)->get();
    }

    public static function getByConditions($params = [], bool $is_paginate = false, int $no_of_rows = 10)
    {
        $query = self::query();
        $q = $params['q'] ?? '';

        if(count($params)){
            $query->where($params);
        }

        if($q) {
            $query->where(function($query) use ($q) {
                $query->where('mobile', "$q%")
                    ->orWhere('name', "$q%")
                    ->orWhere('email', "$q%");
            });
        }

        $query->orderBy('id', 'asc');

        return $is_paginate ? $query->paginate($no_of_rows) : $query->get();
    }

    public static function findById(int $seller_id)
    {
        return self::find($seller_id);
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }

    public function tariffPackages()
    {
        return $this->tariff->hasMany(TariffPackage::class,  'tariff_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'seller_id', 'id');
    }

    public function addBalance(int $amount)
    {
        $this->balance = $this->balance + $amount;
        $this->save();
    }

    public function addCommission(int $amount)
    {
        $this->commission = $this->commission + $amount;
        $this->save();
    }

    public function deductBalance(int $amount)
    {
        $this->balance = $this->balance - $amount;
        $this->save();
    }

    public function deductCommission(int $amount)
    {
        $this->commission = $this->commission - $amount;
        $this->save();
    }

    public function getPackagesAndDetails(int $is_active = null, $with_user_count = false)
    {
        $query = $this->tariffPackages();
        if(!is_null($is_active)) {
            $query->where('is_active', $is_active);
        }
        $tariff_packages = $query->get();
        $data = [];
        foreach ($tariff_packages as $tp) {
            $package = $tp->package;
            $d = [
                'id' => $tp->package_id,
                'name' => $package->name,
                'price' => $package->price,
                'cost' => $tp->cost,
                'valid' => $package->valid,
                'sort' => $package->sort
            ];

            if($with_user_count) {
                $d['users'] = $package->users()->count();
            }

            $data[] = $d;
        }
        usort($data, function($a, $b) {
            return $a['sort'] <=> $b['sort'];
        });

        return $data;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'seller_id', 'id');
    }
}
