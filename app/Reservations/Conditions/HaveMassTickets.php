<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveMassTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        $name = $user->isSignedIn() ? 'You don\'t' : "$user->name doesn't";

        return $user->tickets()->mass($event->start) <= 0
            ? ConditionOutput::deny()->message("$name have a ticket")
            : ConditionOutput::allow();
    }
}