<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveMassTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return $user->tickets()->mass($event->start) <= 0
            ? ConditionOutput::deny()->message("$user->name doesn't have a ticket")
            : ConditionOutput::allow();
    }
}