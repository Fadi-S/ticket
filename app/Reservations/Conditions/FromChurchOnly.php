<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class FromChurchOnly implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return $user->church_id == 1
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message(__('Your account must be activated to make reservations'));
    }
}
