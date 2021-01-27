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

    }

}