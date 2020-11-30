<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class ReservedByAdmin implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        $admin = auth()->user();

        return $admin->hasRole('super-admin') && $admin->id != $user->id
            ? ConditionOutput::allow()
            : ConditionOutput::undecided();
    }
}