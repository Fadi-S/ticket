<?php


namespace App\Models\Mass;


trait MassMethods
{

    public function reservedPlaces()
    {
        $users_total = 0;

        foreach ($this->reservations()->with("users")->get() as $reservation)
            $users_total += $reservation->users()->count();

        return $users_total;
    }

}
