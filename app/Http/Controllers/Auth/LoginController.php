<?php

namespace App\Http\Controllers\Auth;

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

            preg_match('/(?P<number>(01)[0-9]{9})/', $value, $matches);
            $value = '+2' . $matches['number'];
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
