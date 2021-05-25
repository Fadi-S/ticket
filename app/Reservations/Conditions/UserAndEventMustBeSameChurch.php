<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class UserAndEventMustBeSameChurch implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return $event->church_id === $user->church_id
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message(__('User and event must be in the same church'));
    }
}
