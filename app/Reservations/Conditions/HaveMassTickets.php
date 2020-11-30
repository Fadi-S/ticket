<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveMassTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        $maxPerMonth = $event->maxReservations();
        if($maxPerMonth == null)
            return ConditionOutput::allow();

        return $user->reservationsFromMonth($event->start)->count() >= $maxPerMonth
            ? ConditionOutput::deny()->message("$user->name doesn't have a ticket")
            : ConditionOutput::allow();
    }
}