<?php

namespace App\Helpers;

class GoogleAPI {

    private String $apiKey;

    public function __construct()
    {
        $this->apiKey = config('settings.google_api_token');
    }

    public function sendVerificationCode($phone, $recaptchaToken)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/sendVerificationCode?key=' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'phoneNumber' => $phone,
            'recaptchaToken' => $recaptchaToken
        ]));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public function verifyCode($code, $sessionInfo)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'sessionInfo' => $sessionInfo,
            'code' => $code
        ]));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close($ch);

        return isset(json_decode($result, true)['idToken']);
    }

}