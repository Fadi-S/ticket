<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $dates = ['start', 'end'];

    public function type()
    {
        return $this->belongsTo(EventType::class, 'type_id');
    }

    public static function current($typeId, Carbon $date=null)
    {
        $date ??= now();

        //$date = $date->format('Y-m-d H:i:s');

        return \Cache::tags('periods')->remember('period.' . $typeId . '.' . $date->format('Y-m-d'), now()->addHour(),
            fn() => self::where('start', '<=', $date)
                ->where('end', '>=', $date)
                ->where('type_id', '=', $typeId)
                ->first()
        );
    }

    public static function getLatest($latest = 2)
    {
        return \Cache::tags('periods')->remember('periods.current', now()->addHour(),
            fn() => self::latest('start')
                ->where('end', '>=', now())
                ->limit($latest)
                ->get()
        );
    }

    public function setStartAttribute($start)
    {
        $this->attributes['start'] = Carbon::parse($start)->startOfDay();
    }

    public function setEndAttribute($end)
    {
        $this->attributes['end'] = Carbon::parse($end)->endOfDay();
    }

    public function isNow() : bool
    {
        return now()->isBetween($this->start, $this->end);
    }

    public function types()
    {
        return $this->belongsToMany(EventType::class, 'period_type', 'type_id', 'period_id')
            ->withPivot('max_reservations');
    }
}
