<?php


namespace App\Helpers;


use App\Models\Reservation;
use App\Models\User\Ticket;
use App\Reservations\ConditionOutput;

trait CanReserveInEvents
{
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservedTickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'reserved_by');
    }

    public function tickets() : Ticket
    {
        return new Ticket($this);
    }

    public function reserveIn($ticket, $output=null, $create=true)
    {
        if(! $output) {
            $output = $this->canReserveIn($ticket->event);

            if (is_null($output)) {
                flash()->error("Something went wrong for $this->name");

                return false;
            }
        }

        if ($output->hasMessage())
            flash()->error($output->message());

        if ($output->isDenied())
            return false;

        \Cache::tags('ticket.users')->forget('tickets.users.' . $this->id);

        $reservation = [
            'ticket_id' => $ticket->id,
            'user_id' => $this->id,
            'is_deacon' => $ticket->event->hasDeacons && $this->isDeacon(),
            ] + ($output->body() ?? []);

        if($create) {
            $reservation = new Reservation($reservation);

            $this->reservations()->save($reservation);
        }

        return $reservation;
    }

    public function canReserveIn($event)
    {
        $output = null;

        foreach ($event->conditions() as $condition)
        {
            $output = app($condition)->check($event, $this);

            if($output->shouldContinue())
                continue;

            break;
        }

        return $output;
    }
}
