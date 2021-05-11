<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class MustHaveNationalID implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        $name = $user->isSignedIn() ? __("You") : $user->locale_name;

        return (!! $user->national_id)
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message(__(':name must write a national ID', ['name' => $name]));
    }
}
