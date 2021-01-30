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
        $phone = $request->phone;

        preg_match('/(?P<number>(01)[0-9]{9})/', $phone, $matches);
        $phone = '+2' . $matches['number'];

        $request->request->set('phone', $phone);

        $this->validate($request, [
            'phone' => 'required',
            'regex:/(01)[0-9]{9}/',
            'exists:users',
        ]);

        $reCaptcha = $request->reCaptcha;

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

            \Session::put('phone', $phone);

            return ['message' => 'success'];
        }

        return response($response, 400);
    }
}
