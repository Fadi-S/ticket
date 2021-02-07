<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class NotAlreadyReserved implements ConditionContract
{

    public function check($event, $user) : ConditionOutput
    {
        $name = $user->isSignedIn() ? 'You are' : "$user->name is";

        return $user->reservations()
            ->whereHas('ticket', fn($query) => $query->where('event_id', $event->id))
            ->exists()
            ? ConditionOutput::deny()->message("$name already reserved in this event")
            : ConditionOutput::undecided();
    }
}