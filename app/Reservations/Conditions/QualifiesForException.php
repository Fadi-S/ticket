<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;
use Carbon\Carbon;

class QualifiesForException implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if(!$event->allowsException())
            return ConditionOutput::undecided(['is_exception' => false]);

        return abs(Carbon::now()->diffInHours($event->start))
            <= $event->hoursForException()
            ? ConditionOutput::allow(['is_exception' => true])
            : ConditionOutput::undecided(['is_exception' => false]);
    }
}