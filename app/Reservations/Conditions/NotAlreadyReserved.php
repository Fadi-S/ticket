<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class NotAlreadyReserved implements ConditionContract
{

    public function check($event, $user) : ConditionOutput
    {
        return $user->reservations()->where('event_id', $event->id)->exists()
            ? ConditionOutput::deny()->message("$user->name is already reserved in this event")
            : ConditionOutput::undecided();
    }
}