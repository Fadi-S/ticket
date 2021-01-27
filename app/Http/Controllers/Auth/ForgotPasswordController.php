<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GoogleAPI;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendVerificationCode(Request $request)
    {
        $this->validate($request, [
            'phone' => 'regex:/(01)[0-9]{9}/|exists:users',
        ]);

        $phone = $request->phone;
        $reCaptcha = $request->reCaptcha;

        if(!str_starts_with('+2', $phone))
            $phone = '+2' . $phone;

        $api = new GoogleAPI();

        $response = $api->sendVerificationCode($phone, $reCaptcha);

        \DB::table('phone_verifications')
            ->where('phone', $phone)
            ->delete();

        \DB::table('phone_verifications')->insert([
            'phone' => $phone,
            'reCaptcha' => $response['sessionInfo'],
        ]);
    }
}
