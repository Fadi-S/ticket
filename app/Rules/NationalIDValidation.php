<?php

namespace App\Rules;

use App\Helpers\DataFromNationalID;
use Illuminate\Contracts\Validation\Rule;

class NationalIDValidation implements Rule
{
    private $message;

    private $gender;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($gender=null)
    {
        $this->gender = $gender;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(\Str::length($value) != 14 || !is_numeric($value) || !$nid = DataFromNationalID::create($value)) {
            $this->message = __('validation.regex');

            return false;
        }

        if(! is_null($this->gender)) {
            $natIdGender = !! $nid->gender();

            if($this->gender != $natIdGender) {
                $this->message = __("Gender doesn't match national id");

                return false;
            }
        }

        if(!$nid->birthday() || $nid->birthday()->gte(now())) {
            $this->message = __('validation.regex', ['attribute' => __('validation.attributes.national_id')]);

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
