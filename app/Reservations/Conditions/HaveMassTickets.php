<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveMassTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        $name = $user->isSignedIn() ? __("You don't") : __(":name doesn't", ['name' => $user->locale_name]);

        return $user->tickets()->mass($event->start) == 0
            ? ConditionOutput::deny()->message(__(":name have a ticket", ['name' => $name]))
            : ConditionOutput::allow();
    }
}
