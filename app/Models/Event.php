<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "events";
    protected $fillable = ["start", "end", "number_of_places", "description", "type_id"];
    protected $dates = ['start', 'end'];
    protected static $logFillable = true;

    public function reservedPlaces()
    {
        $users_total = 0;

        foreach ($this->reservations as $reservation)
            $users_total += $reservation->users->count();

        return $users_total;
    }

    public function getFormattedDateAttribute()
    {
        return $this->start->format("l, dS F Y");
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start->format("H:i A") . " - " .$this->end->format("H:i A");
    }

    public function type()
    {
        return $this->belongsTo(EventType::class, "type_id");
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, "event_id");
    }
}
