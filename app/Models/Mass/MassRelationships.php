<?php


namespace App\Models\Mass;


use App\Models\EventType\EventType;

trait MassRelationships
{

    public function type()
    {
        return $this->belongsTo(EventType::class, "type_id");
    }

}
