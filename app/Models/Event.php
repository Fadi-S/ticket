<?php

namespace App\Models;

use Database\Factories\MassFactory;
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

    protected $with = ['type'];

    public function reservedPlaces()
    {
        return $this->reservations->count();
    }

    public function getReservationsLeftAttribute()
    {
        return $this->number_of_places - $this->reservations()->count();
    }

    public function getFormattedDateAttribute()
    {
        return $this->start->format("l, dS F Y");
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start->format("h:i A") . " - " .$this->end->format("h:i A");
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
