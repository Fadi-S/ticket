<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class ReservedByAdmin implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if(!auth()->user()->can('reservations.bypass'))
            return ConditionOutput::undecided();

        if($user->isDeacon()) {
            $left = $event->deaconReservationsLeft;
            $maximum = $event->deaconNumber;
        } else {
            $left = $event->reservationsLeft;
            $maximum = $event->number_of_places;
        }

        if($left <= 0) {
            $overload = abs($left) + 1;

            if(1 - ($maximum / ($maximum + $overload)) >= $event->overload) {
                return ConditionOutput::deny()->message(__('Event is overloaded!'));
            }
        }

        return !$user->isSignedIn()
            ? ConditionOutput::allow()
            : ConditionOutput::undecided();
    }
}
