<?php


namespace App\Models\Reservation;


use App\Models\Event\Event;
use App\Models\User\User;

trait ReservationRelationships
{

    public function users()
    {
        return $this->belongsToMany(User::class, "reservation_user", "user_id", "reservation_id");
    }

    public function event()
    {
        return $this->belongsTo(Event::class, "event_id");
    }

}
