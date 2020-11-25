<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Reservation extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ["event_id", "secret", "reserved_at"];
    protected $dates = ['reserved_at'];
    protected static $logFillable = true;

    public function users()
    {
        return $this->belongsToMany(User::class, "reservation_user", "user_id", "reservation_id");
    }

    public function event()
    {
        return $this->belongsTo(Event::class, "event_id");
    }
}
