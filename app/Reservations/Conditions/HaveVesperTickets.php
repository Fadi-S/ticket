<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveVesperTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        $name = $user->isSignedIn() ? 'You don\'t' : "$user->name doesn't";

        return $user->tickets()->vesper($event->start) == 0
            ? ConditionOutput::deny()->message("$name have a ticket")
            : ConditionOutput::allow();
    }
}