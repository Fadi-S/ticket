<?php


namespace App\Helpers;


use App\Models\Reservation;
use App\Models\User\Ticket;

trait CanReserveInEvents
{
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function tickets() : Ticket
    {
        return new Ticket($this);
    }

    public function reserveIn($ticket)
    {
        $output = $this->canReserveIn($ticket->event->specific());

        if(is_null($output)) {
            flash()->error("Something went wrong for $this->name");

            return false;
        }

        if($output->hasMessage())
            flash()->error($output->message());

        if($output->isDenied())
            return false;

        $reservation = new Reservation(
            array_merge([
                'ticket_id' => $ticket->id
            ], $output->body() ?? []));

        /*if($this->email) {
            \Mail::raw("Hello {$this->name}, \n\nYou have a reservation in "
                . $ticket->event->start->format('l, jS \o\f F') . "'s " . $ticket->event->type->name,

                fn($message) => $message->to($this->email)
                    ->subject($ticket->event->type->name . ' Reservation Invoice')
            );
        }*/

        $this->reservations()->save($reservation);

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