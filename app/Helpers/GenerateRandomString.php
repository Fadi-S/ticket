<?php

namespace App\Helpers;

class GenerateRandomString {

    private int $length;

    public function __construct($length = 32)
    {
        $this->length = $length;
    }

    public function handle()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $this->length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}