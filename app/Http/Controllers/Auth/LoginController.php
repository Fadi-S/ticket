<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\NormalizePhoneNumber;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $value = $request->input('email');

        $field = 'username';

        if(filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }

        if(is_numeric($request->input('email'))){
            $field = 'phone';

            $value = NormalizePhoneNumber::create($value)->handle();
        }

        $credentials = [
            $field => $value,
            'password' => $request->input('password')
        ];

        if($this->guard()->attempt($credentials, true))
            return true;

        return false;
    }
}
