<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class ReservedByAdmin implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return auth()->user()->can('reservations.bypass') && !$user->isSignedIn()
            ? ConditionOutput::allow()
            : ConditionOutput::undecided();
    }
}