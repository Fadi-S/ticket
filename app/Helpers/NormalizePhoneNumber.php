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
        $phone = ArabicNumbersToEnglish::create($phone)->handle();

        $phone = str_replace(' ', '', $phone);

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
