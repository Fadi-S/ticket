<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveKiahkTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return $user->tickets()->kiahk($event->start) <= 0
            ? ConditionOutput::deny()->message("$user->name doesn't have a ticket")
            : ConditionOutput::allow();
    }
}