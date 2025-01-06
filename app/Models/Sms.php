<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'sms';

    public static function getAll(string $order_by = 'desc')
    {
        return self::orderBy('id', $order_by)->get();
    }

    public static function getByConditions($params = [], bool $is_paginate = false, int $no_of_rows = 10)
    {
        $query = self::query();
        if(count($params)){
            $query->where($params);
        }

        $query->orderBy('id', 'desc');
        return $is_paginate ? $query->paginate($no_of_rows)->appends($params) : $query->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

}
