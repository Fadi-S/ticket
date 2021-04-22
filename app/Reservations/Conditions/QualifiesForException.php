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

        return now()->gte($event->start->subDay()->hours(9+12))
            ? ConditionOutput::allow(['is_exception' => true])
            : ConditionOutput::undecided(['is_exception' => false]);
    }
}
