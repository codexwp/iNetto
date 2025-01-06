<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const V_UNIT_MONTH = 'month';
    const V_UNIT_DAY = 'day';
    const V_UNIT_LIST = [self::V_UNIT_MONTH, self::V_UNIT_DAY];

    public function getValidAttribute()
    {
        return $this->validity . ' ' . $this->validity_unit;
    }

    public static function getAll(int $server_id = null, string $sort_by = 'asc')
    {
        $query = self::query();
        if($server_id)
            $query->where('server_id', $server_id);

        return $query->orderBy('sort', $sort_by)->get();
    }

    public static function getByConditions($params = [], bool $is_paginate = false, int $no_of_rows = 10)
    {
        $query = self::query();

        if($params)
            $query->where($params);

        $query->orderBy('sort', 'asc');

        return $is_paginate ? $query->paginate($no_of_rows) : $query->get();

    }

    public function users()
    {
        return $this->hasMany(User::class, 'package_id', 'id');
    }

    public function serverProfile()
    {
        return $this->hasOne(ServerProfile::class, 'name', 'profile');
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

}
