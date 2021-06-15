<?php


namespace App\Helpers;


class GetUserLoginField
{
    private $value;

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function create($value)
    {
        return new self($value);
    }

    public function handle()
    {
        $field = 'username';
        $value = $this->value;

        if(filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {

            $value = ArabicNumbersToEnglish::create($this->value)->handle();

            if(is_numeric($value)) {
                if(preg_match('/'. StandardRegex::NATIONAL_ID . '/', $this->value)) {
                    $field = 'national_id';

                }else {
                    $field = 'phone';

                    $value = NormalizePhoneNumber::create($value)->handle();
                }
            }
        }

        return [
            $field,
            $value,
        ];
    }

}
