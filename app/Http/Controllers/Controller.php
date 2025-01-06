<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function filter(array $params): array
    {
        foreach ($params as $key => $value)
        {
            if(is_null($value) || empty($value))
            {
                unset($params[$key]);
            }
        }
        return $params;
    }

    public function successResponse(string $message, mixed $data = null)
    {
        $body = array(
            'success' => true,
            'message' => $message,
            'data' => $data
        );
        return response()->json($body);
    }

    public function errorResponse(string $message, array $errors = [], int $code = 400)
    {
        $body = array(
            'success' => false,
            'message' => $message
        );
        if($errors)
            $body['data'] = $errors;

        return response()->json($body, $code);
    }
}
