<?php


namespace App\Helpers;


class DataFromNationalID
{
    private $nationalId;

    private function __construct($nationalId)
    {
        $this->nationalId = $nationalId;
    }

    public static function create($nationalId)
    {
        $nationalId = ArabicNumbersToEnglish::create($nationalId)->handle();

        return new self($nationalId);
    }

    public function gender()
    {
        $number = substr($this->nationalId, -5, 4);

        return $number % 2 !== 0;
    }

    public function birthday()
    {

    }
}
