<?php

namespace App\Listeners;

use App\Events\TicketReserved;
use App\Notifications\ReservationConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvoiceNotification implements ShouldQueue
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TicketReserved  $event
     * @return void
     */
    public function handle(TicketReserved $event)
    {
        $event->ticket->reservations->each(function ($reservation) use ($event) {
            $user = $reservation->user;
            if($user->email) {
                $user->notify(new ReservationConfirmed($event->ticket));
            }
        });
    }
}
