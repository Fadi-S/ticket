<?php


namespace App\Models\Event;

use App\Models\EventType\EventType;
use App\Models\Reservation\Reservation;

trait EventRelationships
{

    public function type()
    {
        return $this->belongsTo(EventType::class, "type_id");
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, "event_id");
    }

}
