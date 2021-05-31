<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveEventTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if($event->type->isUnlimited()) {
            return ConditionOutput::allow();
        }

        $name = $user->isSignedIn() ? __("You don't") : __(":name doesn't", ['name' => $user->locale_name]);

        return $user->tickets()->event($event->type_id, $event->start) == 0
            ? ConditionOutput::deny()->message(__(":name have a ticket", ['name' => $name]))
            : ConditionOutput::allow();
    }
}
