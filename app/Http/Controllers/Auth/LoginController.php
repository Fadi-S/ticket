<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ArabicNumbersToEnglish;
use App\Helpers\GetUserLoginField;
use App\Helpers\NormalizePhoneNumber;
use App\Helpers\StandardRegex;
use App\Http\Controllers\Controller;
use App\Models\Login;
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

        [$field, $value] = GetUserLoginField::create($value)->handle();

        $credentials = [
            $field => $value,
            'password' => $request->input('password')
        ];

        return $this->guard()->attempt($credentials, true);
    }

    protected function authenticated(Request $request, $user)
    {
        Login::saveCurrentSession();
    }
}
