<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Server extends Model
{
    use HasFactory;

    protected $guarded =  ['id'];

    public static $temp_client = null;
    public static $temp_server_id = null;

    public function getIpPortAttribute()
    {
        return $this->ip . ':' . $this->port;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt_decrypt($value);
    }

    public function getPasswordAttribute($value)
    {
        return encrypt_decrypt($value, true);
    }

    public static function findById(int $id)
    {
        return self::find($id);
    }

    public static function createOrUpdate(array $data)
    {
        try{
            $server = self::find(1);
            $data['ssl'] = $data['ssl'] ?? 0;

            $data['is_active'] = $data['is_active'] ?? 0;
            if($server)
                $server->update($data);
            else
                Server::create($data);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

}
