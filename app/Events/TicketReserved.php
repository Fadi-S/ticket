<?php

namespace App\Events;

use App\Models\Ticket;
use App\Notifications\ReservationConfirmed;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketReserved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventId;
    public $reserved;

    public $ticket;

    /**
     * Create a new event instance.
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->eventId = $ticket->event_id;

        $this->reserved = $ticket->event->reservations_left;

        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tickets');
    }
}
