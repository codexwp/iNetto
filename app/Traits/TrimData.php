<?php

namespace App\Traits;

trait TrimData
{
    /**
     * リクエストフォームの全角スペースを除去
     * @param $request
     * @return mixed
     */
    public static function trimData($request)
    {
        $trim_data = $request->all();
        $trimmed = [];
        foreach($trim_data as $key => $value)
        {
            $trimmed[$key] = preg_replace('/(^\s+)|(\s+$)/u', '', $value);
        }
        return $request->merge($trimmed);
    }
}
