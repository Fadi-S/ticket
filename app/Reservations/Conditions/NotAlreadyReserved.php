<?php


namespace App\Reservations\Conditions;

use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class NotAlreadyReserved implements ConditionContract
{

    public function check($event, $user) : ConditionOutput
    {
        $name = $user->isSignedIn() ? __('You are') : __(":name is", ['name' => $user->locale_name]);

        return $user->reservations()
            ->whereHas('ticket', fn($query) => $query->where('event_id', $event->id))
            ->exists()
            ? ConditionOutput::deny()->message(__(":name already reserved in this event", ['name' => $name]))
            : ConditionOutput::undecided();
    }
}