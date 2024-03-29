<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class EventDateHasNotPassed implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return $event->end->greaterThanOrEqualTo(now())
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()->message(__('This event has passed'));
    }
}