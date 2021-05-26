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
    protected $with = ['types'];

    public static function current(Carbon $date=null)
    {
        $date ??= now();

        //$date = $date->format('Y-m-d H:i:s');

        return self::where('start', '<=', $date)
            ->where('end', '>=', $date)
            ->first();
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
