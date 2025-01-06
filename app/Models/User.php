<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\Pear2Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
        'secret'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'expire_at' => 'datetime',
        'start_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getByConditions($params = [], bool $is_paginate = false, int $no_of_rows = 10)
    {
        $query = self::query();
        $q = $params['q'] ?? '';
        $is_expired = $params['is_expired'] ?? '';
        unset($params['q'], $params['is_expired']);

        if(count($params)){
            $query->where($params);
        }

        if($q) {
            $query->where(function($query) use ($q) {
               $query->where('username', 'LIKE', "%$q%")
                    ->orWhere('mobile', "%$q%")
                   ->orWhere('name', "%$q%")
                   ->orWhere('email', "%$q%");
            });
        }

        if($is_expired == '1') {
            //All Expired
            $query->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') < ?", [now()]);
        } else if($is_expired == '2') {
            //Not Expired
            $query->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') >= ?", [now()]);
        } else if($is_expired == '3') {
            //Expire in Today
            $query->whereDate('expire_at', now()->format('Y-m-d'));
        } else if($is_expired == '4') {
            //Expire in Tomorrow
            $query->whereDate('expire_at', now()->addDay()->format('Y-m-d'));
        } else if($is_expired == '5') {
            //Expire in 3 Days
            $query->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') > ?", [now()])
                  ->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') <= ?", [now()->addDays(3)]);
        } else if($is_expired == '6') {
            //Expire in 5 Days
            $query->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') > ?", [now()])
                ->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') <= ?", [now()->addDays(5)]);
        }

        $query->orderBy('id', 'asc');

        return $is_paginate ? $query->paginate($no_of_rows) : $query->get();
    }

    public static function getAll(string $order_by = 'desc')
    {
        return self::orderBy('created_at', $order_by)->get();
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public static function generateUsername()
    {
        $start_user_id = 1000;
        $last_user = self::orderBy('id', 'desc')->first();
        return $last_user ? $start_user_id+$last_user->id+1 : $start_user_id+1;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
        $this->attributes['secret'] = encrypt_decrypt($value);
    }

    public function getSecretAttribute($value)
    {
        return encrypt_decrypt($value, true);
    }

    public function getExpireAtAttribute($value)
    {
        return $value;
    }

    public function getDetails(User $user)
    {
        return $user->with(['seller', 'package', 'payments']);
    }

    public static function destroyWithMikrotik($user)
    {
        $server = Server::findById(1);
        if($server->is_active)
        {
            $p2s = new Pear2Service($server->id);
            if(!$p2s->client) {
                throw new \Exception($p2s->error);
            }
            $p2s->deleteClient($user->username);
        }
        $user->delete();
    }

    public static function synchronize($user, $p2s = null)
    {
        $server = Server::findById(1);
        if(!$server->is_active) {
            return true;
        }

        $package = $user->package;
        $profile = $package->serverProfile;
        if($profile == null) {
            throw new \Exception("Package profile is not found");
        }

        $p2s = $p2s ?? new Pear2Service(1);
        if($p2s->client == null) {
            throw new \Exception($p2s->error);
        }

        $status = intval($user->is_active_client);
        $client = $p2s->findClient($user->username);
        if($client == null){
            $p2s->createClient($user->username, $user->secret, $package->profile);
        } else {
            $p2s->updateClient($client, ['password' => $user->secret,'profile'=> $package->profile, 'disabled' => $status ? 'false' : 'true']);
        }

        return true;
    }

    public static function getByUserIds(array $user_ids)
    {
        return self::whereIn('id', $user_ids)->get();
    }
}
