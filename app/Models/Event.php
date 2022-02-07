<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use function PHPUnit\Framework\isNull;

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

    public function scopePublic($query)
    {
        $query->upcoming()
            ->published()
            ->select('id', 'description', 'start', 'end');
    }

    public function period()
    {
        return Period::current($this->type_id, $this->start);
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
                ['start', '<', now()->addMinutes(30)],
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
        if(!$deacon && isset($this->total_reservations_count)) {
            return $this->total_reservations_count;
        }

        if($deacon && isset($this->total_reservations_count_deacon)) {
            return $this->total_reservations_count_deacon;
        }

        return \DB::select('Select COALESCE(sum(res_count.count_res), 0) as total
        from (select (SELECT count(*) from reservations WHERE reservations.ticket_id=tickets.id and reservations.is_deacon=?)
         as count_res from tickets where event_id=?)
            as res_count', [$deacon, $this->id])[0]->total;
    }

    public function scopeWithReservationsCount($query)
    {
        if(isNull($query->getQuery()->columns))
            $query->select('*');

        $queryString = '
        (select
        COALESCE(sum(res_count.count_res), 0) as total
        from (select (SELECT count(*) from reservations WHERE reservations.ticket_id=tickets.id and reservations.is_deacon=?)
         as count_res from tickets where event_id=events.id)
            as res_count
            )
        ';

        $query->selectRaw($queryString .' as total_reservations_count_deacon', [1])
            ->selectRaw($queryString . ' as total_reservations_count', [0]);
    }

    public static function getByType($type, $pagination=10)
    {
        $currentPage = request()->get('page', 1);

        return \Cache::tags('events')
            ->remember("events.$type.page.$currentPage", now()->addMinutes(15),
                fn() => self::typeId($type)
                    ->orderBy('start')
                    ->withReservationsCount()
                    ->upcoming()
                    ->paginate($pagination)
            );
    }

    public static function clearCache()
    {
        \Cache::tags('events')->flush();
    }

    /**  @deprecated */
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

    public function conditions()
    {
        return $this->type->getConditions();
    }
}
