<?php

namespace App\Models;

use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use SoftDeletes, LogsActivity, HasFactory;

    protected $table = "events";
    protected $fillable = ["start", "end", "number_of_places", "deacons_number", "description", "type_id", "overload", "published_at"];
    protected $dates = ['start', 'end', 'published_at'];

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected $with = ['type'];

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

    public function scopeTypeId($query, $type)
    {
        return $query->where('type_id', '=', $type);
    }

    public function period()
    {
        return Period::current($this->start);
    }

    public function getDeaconNumberAttribute()
    {
        return $this->deacons_number;
    }

    public function getHasDeaconsAttribute()
    {
        return $this->type->has_deacons;
    }

    public static function getCurrent()
    {
        return \Cache::remember('events.current', now()->addMinutes(10),
            fn() => self::where([
                ['start', '<', now()],
                ['end', '>', now()]
            ])->get()
        );
    }

    public function allowsException()
    {
        return $this->type->allows_exception;
    }

    /** @deprecated  **/
    public function reservedPlaces()
    {
        $count = 0;

        foreach ($this->tickets as $ticket)
            $count += $ticket->reservations_count;

        return $count;
    }

    public function getReservationsLeftAttribute()
    {
        return $this->number_of_places - $this->getReservationsCount();
    }

    public function getDeaconReservationsLeftAttribute()
    {
        if($this->hasDeacons)
            return $this->deacons_number - $this->getReservationsCount(true);

        return $this->reservationsLeft;
    }

    public function getReservationsCount($deacon=false)
    {
        $count = 0;

        $tickets = $this->tickets()
            ->withCount([
                'reservations' =>
                    fn($query) => $query->where('is_deacon', '=', $deacon)
            ])
            ->whereHas('reservations', fn ($query) =>
                $query->where('is_deacon', '=', $deacon)
            )->get();

        foreach ($tickets as $ticket) {
            $count += $ticket->reservations_count;
        }

        return $count;
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
        return $this->start->translatedFormat("l, d M y");
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start->translatedFormat("h:i A") . " -> " .$this->end->translatedFormat("h:i A");
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
        return $this->start->lte(now()->addHours(config('settings.cancellation_before_event_hours'))) || $this->hidden_at != null;
    }

    /** @deprecated  **/
    public function specific()
    {
        return $this;
    }


    /** @deprecated  **/
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

    public function conditions()
    {
        return $this->type->getConditions();
    }
}
