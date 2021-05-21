<?php

namespace App\Reservations;


interface EventContract
{
    static public function maxReservations() : int;

    static public function allowsException() : bool;

    public function conditions();
}
