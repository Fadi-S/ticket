<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class EnoughSpaceInEvent implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if($user->isDeacon() && $event->hasDeacons)
            return ConditionOutput::undecided();

        return $event->reservationsLeft >= 1
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message(__('No places left in this event'));
    }
}
