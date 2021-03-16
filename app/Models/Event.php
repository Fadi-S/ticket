<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function scopeUpcoming($query)
    {
        return $query->whereDate('end', '>=', now());
    }

    public function reservedPlaces()
    {
        $count = 0;

        foreach ($this->tickets as $ticket)
            $count += $ticket->reservations_count;

        return $count;
    }

    public function getReservationsLeftAttribute()
    {
        $count = 0;
        $this->load('tickets');

        foreach ($this->tickets as $ticket)
            $count += $ticket->reservations_count;

        return $this->number_of_places - $count;
    }

    public function getFormattedDateAttribute()
    {
        return $this->start->format("l, dS F Y");
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start->format("h:i A") . " -> " .$this->end->format("h:i A");
    }

    public function type()
    {
        return $this->belongsTo(EventType::class, "type_id");
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id');
    }

    public function hasPassed()
    {
        return $this->start->lte(now());
    }

    public function specific()
    {
        return app($this->type->model)->find($this->id);
    }

    public function eventOrderInDay()
    {
        $order = [
            1 => 'أول',
            2 => 'ثاني',
            3 => 'تالث',
            4 => 'رابع',
            5 => 'خامس',
            6 => 'سادس',
        ];

        $eventsCount = Event::whereBetween('start', [$this->start->startOfDay(), $this->start])
            ->where('type_id', $this->type_id)
            ->count();

        if(!in_array($eventsCount, array_keys($order)))
            return '';

        return $order[$eventsCount];
    }
}
