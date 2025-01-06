<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function get(string|array $name)
    {
        $query = self::query();
        if(is_array($name)){
            $configs = $query->whereIn('name', $name)->pluck('value', 'name')->toArray();
            foreach ($configs as $name => $value) {
                $configs[$name] = is_json($value) ? json_decode($value, true) : $value;
            }
            return $configs;
        }else{
            $config = $query->where('name', $name)->first();
            if($config) {
                return is_json($config->value) ? json_decode($config->value, true) : $config->value;
            }
            return null;
        }
    }

    public static function set(string $name, string|array $value)
    {
        $config = self::firstOrNew(['name' => $name]);
        $config->value = is_array($value) ? json_encode($value) : $value;
        $config->save();
    }
}
