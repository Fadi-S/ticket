<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArabicOnly implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return $this->isArabic($value);
    }

    private function uniord($u)
    {
        $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }
    private function isArabic($str)
    {
        if(mb_detect_encoding($str) !== 'UTF-8') {
            $str = mb_convert_encoding($str,mb_detect_encoding($str),'UTF-8');
        }

        preg_match_all('/.|\n/u', $str, $matches);
        $chars = $matches[0];
        foreach($chars as $char) {
            $pos = $this->uniord($char);

            if(! ($pos >= 1536 && $pos <= 1791 || $pos == 32 || $pos == 46) ) {
                return false;
            }
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
        return __('Only arabic letters are allowed.');
    }
}
