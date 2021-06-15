<?php

namespace App\Http\Controllers\API;

use App\Helpers\GetUserLoginField;
use App\Http\Controllers\Controller;
use App\Models\Login;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        [$field, $value] = GetUserLoginField::create($data['email'])->handle();

        $success = auth()->attempt([
            $field => $value,
            'password' => $data['password'],
        ]);

        if (!$success) {
            return response(['error_message' => __('auth.failed')], 403);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        Login::saveCurrentSession();

        return response(['user' => auth()->user(), 'token' => $token]);
    }

}
