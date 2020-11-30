<?php

namespace App\Reservations;


interface EventContract
{
    public function maxReservations() : int;

    public function hoursForException() : int;

    public function allowsException() : bool;

    public function conditions();
}