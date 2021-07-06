<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class MustHaveFullName implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if($user->isGuest()) {
            return ConditionOutput::undecided();
        }

        return ! ($user->isSignedIn() && $user->hasFirstNameOnly())
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message(__('You must write your full name (3 names).'));
    }
}
