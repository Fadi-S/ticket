<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Fullname implements Rule
{
    private int $number;

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        $number = config('settings.full_name_number');

        $this->number = $number;
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
        return count(explode(' ', $value)) >= $this->number;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You must write your full name (' . $this->number . ' names).');
    }
}
