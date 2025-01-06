<?php

namespace App\Http\Middleware;

use App\Models\Config;
use App\Traits\Install;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsInstalled
{
    use Install;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $status = $this->checkInstall();
        if($status['status'] === false) {
            session()->put('step', 1);
            return redirect()->route("install")->with("error", $status['message']);
        }

        return $next($request);
    }
}
