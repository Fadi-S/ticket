<?php


namespace App\Models\Mass;


trait MassAttributes
{

    public function getFormattedDateAttribute()
    {
        return $this->start->format("l, dS F Y");
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start->format("H:i A") . " - " .$this->end->format("H:i A");
    }

}
