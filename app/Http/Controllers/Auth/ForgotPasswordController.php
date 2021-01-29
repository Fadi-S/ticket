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
            'phone' => 'required',
            'regex:/(01)[0-9]{9}/',
            'exists:users',
        ]);

        $phone = $request->phone;
        $reCaptcha = $request->reCaptcha;

        preg_match('/(?P<number>(01)[0-9]{9})/', $phone, $matches);
        $phone = '+2' . $matches['number'];

        $api = new GoogleAPI();

        $response = $api->sendVerificationCode($phone, $reCaptcha);

        if(isset($response['sessionInfo'])) {

            \DB::table('phone_verifications')
                ->where('phone', $phone)
                ->delete();

            \DB::table('phone_verifications')->insert([
                'phone' => $phone,
                'reCaptcha' => $response['sessionInfo'],
            ]);

            return ['message' => 'success'];
        }

        return response($response, 400);
    }
}
