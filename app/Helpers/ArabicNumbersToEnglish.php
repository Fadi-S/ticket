<?php


namespace App\Helpers;


class ArabicNumbersToEnglish
{
    private $number;

    private function __construct($number)
    {
        $this->number = $number;
    }

    public static function create($number)
    {
        return new self($number);
    }

    public function handle()
    {
        return strtr($this->number, ['٠'=>'0', '١'=>'1', '٢'=>'2', '٣'=>'3', '٤'=>'4', '٥'=>'5', '٦'=>'6', '٧'=>'7', '٨'=>'8', '٩'=>'9']);
    }

}
