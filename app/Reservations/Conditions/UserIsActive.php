<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class UserIsActive implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return $user->isActive()
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message(__('Your account must be activated to make reservations'));
    }
}
