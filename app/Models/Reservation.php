<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Reservation extends Model
{
    use LogsActivity, LogsActivity;

    protected $guarded = [];
    protected $dates = ['registered_at'];
    protected $casts = ['is_exception' => 'boolean'];
    protected $with = ['user'];

    protected static $logFillable = true;
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

    public function isDeacon()
    {
        return !! $this->is_deacon;
    }

    public function cancel()
    {
        activity()->causedBy(auth()->user())
            ->performedOn($this)
            ->withProperties([
                'user_id' => $this->user_id,
                'ticket_id' => $this->ticket_id,
                'event_id' => $this->ticket->event_id,
                'reserved_by' => $this->ticket->reserved_by,
                'reserved_at' => $this->ticket->reserved_at,
                'is_exception' => $this->is_exception ?? false,
            ])
            ->log('canceled');

        if($this->ticket && $this->ticket->refresh()->reservations_count == 1)
            $this->ticket->delete();

        \Cache::forget('tickets.users.' . $this->user_id);

        $this->delete();
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
