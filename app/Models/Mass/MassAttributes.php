<?php


namespace App\Models\Mass;


trait MassAttributes
{

    public function getFormattedDateAttribute()
    {
        return $this->time->format("l, dS F Y");
    }

}
