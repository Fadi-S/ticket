<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GoogleAPI;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    public function confirmVerificationCode(Request $request)
    {
        $phone = $request->phone;
        $code = $request->code;

        $api = new GoogleAPI();

        $sessionInfo = \DB::table('phone_verifications')
            ->where('phone', $phone)
            ->first()
            ->reCaptcha;

        $response = $api->verifyCode($code, $sessionInfo);

        return $response;

        \DB::table('phone_verifications')
            ->where('phone', $phone)
            ->delete();

        $user = User::where('phone', $phone)->first();

        return $response;
    }

}
