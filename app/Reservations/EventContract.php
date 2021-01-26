<?php

namespace App\Reservations;


interface EventContract
{
    static public function maxReservations() : int;

    static public function hoursForException() : int;

    static public function allowsException() : bool;

    static public function conditions();
}