<?php

namespace App\Reservations;

interface ConditionContract
{
    public function check($event, $user) : ConditionOutput;
}