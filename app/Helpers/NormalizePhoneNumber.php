<?php

namespace App\Helpers;

class NormalizePhoneNumber {

    private string $phone;
    private bool $strict;

    private function __construct(string $phone, bool $strict)
    {
        $this->phone = $phone;

        $this->strict = $strict;
    }

    /**
     * @param string $phone Phone Number
     * @param bool $strict set to false if you don't care if the number is complete or not
     * @return static
     */
    public static function create(string $phone, bool $strict=true) : self
    {
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