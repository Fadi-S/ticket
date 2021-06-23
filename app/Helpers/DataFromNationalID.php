<?php


namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Support\Str;

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

        if(! $nationalId || Str::length($nationalId) != 14 || ! is_numeric($nationalId)) {
            return null;
        }

        return new self($nationalId);
    }

    public function gender()
    {
        $number = substr($this->nationalId, -5, 4);

        return $number % 2 !== 0;
    }

    public function birthday()
    {
        $year = substr($this->nationalId, 0, 1) == '3' ? '20' : '19';
        $year .= substr($this->nationalId, 1, 2);

        $month = substr($this->nationalId, 3, 2);

        $day = substr($this->nationalId, 5, 2);

        if($month > 12 || $day > 31) {
            return null;
        }

        return Carbon::parse("$year-$month-$day");
    }

    public function nationalId()
    {
        return $this->nationalId;
    }
}
