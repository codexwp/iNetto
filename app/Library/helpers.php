<?php

if (!function_exists('is_active_menu'))
{
    function is_active_menu($name, $has_submenu = false)
    {
        $route = request()->route()->getName();
        $result = $has_submenu ? 'active open' : 'active';
        return $has_submenu ? (str_contains($route, $name) ? $result : '') : ($route == $name ? $result : '');
    }
}

if (!function_exists('encrypt_decrypt')) {
    function encrypt_decrypt(string $data, bool $is_decrypt = false)
    {
        $ciphering = "AES-128-CTR";
        $options = 0;
        $iv = '5274567891016547';
        $key = env('APP_NAME');
        if ($is_decrypt == false)
            $ed = openssl_encrypt($data, $ciphering, $key, $options, $iv);
        else
            $ed = openssl_decrypt($data, $ciphering, $key, $options, $iv);
        return $ed;
    }
}


if (!function_exists('country_codes')) {
    function country_codes()
    {
        $jsonString = file_get_contents(__DIR__ . '/country_codes.json');
        return json_decode($jsonString, true);
    }
}

if (!function_exists('country_codes_by_iso')) {
    function country_codes_by_iso(string $iso)
    {
        $country_codes = country_codes();
        for($i = 0; $i < count($country_codes); $i++) {
            $country = $country_codes[$i];
            if($country['iso'] == $iso) {
                return $country['code'];
            }
        }
    }
}

if (!function_exists('time_zones')) {
    function time_zones()
    {
        $time_zones = include 'time_zones.php';;
        return $time_zones;
    }
}

if (!function_exists('is_json')) {
    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('get_usernames')) {
    function get_usernames(int $seller_id = null)
    {
        if($seller_id) {
            return \Illuminate\Support\Facades\DB::table('users')->select('id', 'username')->where('seller_id', $seller_id)->pluck('username', 'id')->toArray();
        }
        return \Illuminate\Support\Facades\DB::table('users')->select('id', 'username')->pluck('username', 'id')->toArray();
    }
}
