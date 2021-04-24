<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "events";
    protected $fillable = ["start", "end", "number_of_places", "description", "type_id", "overload", "published_at"];
    protected $dates = ['start', 'end', 'published_at'];
    protected static $logFillable = true;

    protected $with = ['type'];

    public int $deaconNumber = 10;

    public function scopeUpcoming($query)
    {
        return $query->where('end', '>=', now());
    }

    public function scopeVisible($query)
    {
        return $query
            ->where('hidden_at', '=', null)
            ->orWhere('hidden_at', '>=', now());
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
        return $this->number_of_places - $this
                ->reservationsCountForRole('user', 'agent', 'super-admin');
    }

    public function getDeaconReservationsLeftAttribute()
    {
        return $this->deaconNumber - $this->reservationsCountForRole('deacon', 'deacon-admin');
    }

    public function reservationsCountForRole(...$role)
    {
        $count = 0;

        if(!is_array($role)) {
            $role = [$role];
        }

        $tickets = $this->tickets()
            ->withCount([
                'reservations' =>
                    fn($query) => $query->whereHas('user',
                        fn($query) => $query->role($role)
                    )
            ])
            ->whereHas('reservations', function ($query) use ($role) {
                $query->whereHas('user', fn($query) => $query->role($role));
            })->get();

        foreach ($tickets as $ticket) {
            $count += $ticket->reservations_count;
        }

        return $count;
    }

    public function getFormattedDateAttribute()
    {
        return $this->start->format("l, dS F Y");
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start->format("h:i A") . " -> " .$this->end->format("h:i A");
    }

    public function scopePublished($query)
    {
        $query->where('published_at', '<=', now());
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
        return $this->start->lte(now()->addHours(12)) || $this->hidden_at != null;
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
