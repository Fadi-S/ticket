<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class ReservedByAdmin implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if(!auth()->user()->can('reservations.bypass') || $user->isSignedIn())
            return ConditionOutput::undecided();

        if($event->hasDeacons && $user->isDeacon()) {
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

        $confirmation = 'adminBypass.confirmation';

//        dd($user->tickets()->event($event->type_id, $event->start));

        $isNotAllowed = $user->tickets()->event($event->type_id, $event->start) == 0;
        if(!session()->has($confirmation) && $isNotAllowed) {
            return ConditionOutput::confirmation(null, $confirmation)
                ->message(__('Are you sure? :name has surpassed his allowed reservations', ['name' => $user->locale_name]));
        }

        if(! $isNotAllowed) {
            return ConditionOutput::allow();
        }

        return session($confirmation)
            ? ConditionOutput::allow()
            : ConditionOutput::deny()->message(__('Reservations cancelled'));
    }
}
