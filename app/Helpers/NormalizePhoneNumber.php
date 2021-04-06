<?php

namespace App\Helpers;

class NormalizePhoneNumber {

    private $phone;
    private bool $strict;

    private function __construct($phone, bool $strict)
    {
        $this->phone = $phone;

        $this->strict = $strict;
    }

    /**
     * @param $phone
     * @param bool $strict set to false if you don't care if the number is complete or not
     * @return static
     */
    public static function create($phone, bool $strict=true) : self
    {
        $phone = strtr($phone, ['٠'=>'0', '١'=>'1', '٢'=>'2', '٣'=>'3', '٤'=>'4', '٥'=>'5', '٦'=>'6', '٧'=>'7', '٨'=>'8', '٩'=>'9']);

        return new self($phone, $strict);
    }

    public function handle()
    {
        if(!$this->phone || $this->phone == '') {
            return null;
        }

        $regex = ($this->strict)
            ? '/(?P<number>(01)[0-9]{9})/'
            : '/(?P<number>(01)[0-9]{0,9})/';

        preg_match($regex, $this->phone, $matches);

        if(!isset($matches['number'])) {
            return $this->phone;
        }

        return '+2' . $matches['number'];
    }
}