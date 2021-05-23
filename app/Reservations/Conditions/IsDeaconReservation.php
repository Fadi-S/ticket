<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;
use Carbon\Carbon;

class IsDeaconReservation implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if(! $user->isDeacon())
            return ConditionOutput::undecided();

        return $event->deaconReservationsLeft >= 1
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()->message(__('No deacon places left in this event'));
    }
}
