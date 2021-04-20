<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Reservation extends Model
{
    use LogsActivity;

    protected $guarded = [];
    protected $dates = ['registered_at'];
    protected $casts = ['is_exception' => 'boolean'];
    protected $with = ['user'];

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function event()
    {
        return ($this->ticket) ? $this->ticket->event : null;
    }

    public function cancel()
    {
        $this->delete();

        if($this->ticket->refresh()->reservations_count == 0)
            $this->ticket->delete();
    }

    public function changeEventTo($eventId)
    {
        $event = Event::findOrFail($eventId);
        $event = app($event->type->model)->find($event->id);

        $user = $this->user;

        $this->is_exception = true;
        $this->save();

        $output = $user->canReserveIn($event);

        if($output->isDenied()) {
            $this->is_exception = false;
            $this->save();

            flash()->error($output->message());

            return false;
        }

        $this->cancel();

        return $user->reserveIn($event);
    }

    public function of($user)
    {
        if($user->can('tickets.*'))
            return true;

        return $this->ticket->reservedBy->id == $user->id || $this->user_id == $user->id;
    }
}
