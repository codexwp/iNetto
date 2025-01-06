<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $prefix = $request->route()->getPrefix();
        if ($prefix == 'admin') {
            $route_name = 'admin.login';
        } else if ($prefix == 'seller') {
            $route_name = 'seller.login';
        } else {
            $route_name = 'login';
        }
        return $request->expectsJson() ? null : route($route_name);
    }
}
