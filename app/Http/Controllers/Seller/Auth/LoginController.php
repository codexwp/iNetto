<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    protected $redirectTo = '/seller/dashboard';


    public function __construct()
    {
        $this->middleware('guest:seller')->except('logout');
        $this->middleware('auth:seller')->only('logout');
    }

    protected function guard()
    {
        return Auth::guard('seller');
    }

    public function showLoginForm()
    {
        return view('seller.auth.login');
    }

    public function username()
    {
        return 'login';
    }

    public function credentials(Request $request)
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        return [
            $field => $login,
            'password' => $request->input('password'),
            'is_active' => 1
        ];
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/seller/login');
    }


}
