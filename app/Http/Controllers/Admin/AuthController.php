<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view("auth.login");
    }

    protected function attemptLogin(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if($user == null)
            return false;

        if(!$user->can("backend"))
            return false;

        return $this->guard()->attempt($this->credentials($request), $request->filled('remember'));
    }

}
