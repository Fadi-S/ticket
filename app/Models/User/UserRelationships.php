<?php


namespace App\Models\User;


use App\Models\Reservation\Reservation;

trait UserRelationships
{

    public function reservations()
    {
        $this->belongsToMany(Reservation::class, "reservation_user", "user_id", "reservation_id");
    }

}
