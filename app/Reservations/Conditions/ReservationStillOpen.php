<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;
use Carbon\Carbon;

class ReservationStillOpen implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return now()->lte($event->start->subDay()->hour(21))
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message(__('Too late to reserve in this event'));
    }
}