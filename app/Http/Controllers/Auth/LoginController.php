<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'login';
    }

    protected function attemptLogin(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $user_login =  $this->guard()->attempt(
            ['username' => $login, 'password' => $password], $request->boolean('remember')
        );

        if($user_login) { return true; }

        $email_login =  $this->guard()->attempt(
            ['email' => $login, 'password' => $password], $request->boolean('remember')
        );

        if($email_login) { return true; }

        $mobile_login =  $this->guard()->attempt(
            ['mobile' => $login, 'password' => $password], $request->boolean('remember')
        );

        if($mobile_login) { return true; }

        return false;
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
            : redirect('/login');
    }


}
