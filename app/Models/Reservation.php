<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Reservation extends Model
{
    use SoftDeletes, LogsActivity;

    protected $guarded = [];
    protected $dates = ['reserved_at', 'registered_at'];
    protected $casts = ['is_exception' => 'boolean'];

    protected static $logFillable = true;

    public function scopeUser($query)
    {
        if(auth()->user()->hasRole('user'))
            $query->where('user_id', \Auth::id())->orWhere('reserved_by', \Auth::id());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, "event_id");
    }

    public function cancel()
    {
        $this->delete();
    }

    public function changeEventTo($eventId)
    {
        $event = Event::findOrFail($eventId);

        $user = $this->user;

        $this->cancel();

        return $user->reserveIn($event);
    }

    public function of($user)
    {
        return $this->reservedBy->id == $user->id || $this->user->id == $user->id;
    }
}
