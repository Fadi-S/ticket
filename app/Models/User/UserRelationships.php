<?php


namespace App\Models\User;


use App\Models\Reservation;

trait UserRelationships
{

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, "reservation_user", "reservation_id", "user_id");
    }

}
