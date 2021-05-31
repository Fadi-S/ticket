<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class AllowAll implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        return ConditionOutput::allow();
    }
}
