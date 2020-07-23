<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Reservation extends Model
{
    use SoftDeletes, LogsActivity, ReservationRelationships;

    protected $fillable = ["event_id", "secret", "reserved_at"];
    protected $dates = ['reserved_at'];
    protected static $logFillable = true;
}
