<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class HaveBaskhaTickets implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if($user->isDeacon())
        {
            return ConditionOutput::allow();
        }

        $name = $user->isSignedIn() ? __("You don't") : __(":name doesn't", ['name' => $user->locale_name]);

        return $user->tickets()->baskha($event->start) == 0
            ? ConditionOutput::deny()->message(__(":name have a ticket", ['name' => $name]))
            : ConditionOutput::allow();
    }
}
