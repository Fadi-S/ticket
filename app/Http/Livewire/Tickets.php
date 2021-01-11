<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Reservation;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class Tickets extends Component
{
    use WithPagination;

    public $event = 0;
    public $type = 0;
    public $search = '';

    protected $listeners = [
        'cancelReservation' => 'cancelReservation',
        'refresh' => 'render',
    ];

    protected $queryString = [
        'event' => ['except' => 0],
        'type' => ['except' => 0],
        'search' => ['except' => ''],
        'page',
    ];

    public function render()
    {
        return view('livewire.tickets', [
            'tickets' => $this->getTickets(),
            'eventModel' => Event::find($this->event),
            'typeModel' => EventType::find($this->type),
        ]);
    }

    public function cancelReservation(Reservation $reservation)
    {
        if(!$reservation->ticket->event->hasPassed() && $reservation->of(auth()->user()))
            $reservation->cancel();

        $this->emitSelf('refresh');
    }

    public function cancel(Ticket $ticket)
    {
        if(!$ticket->event->hasPassed() && $ticket->of(auth()->user()))
            $ticket->cancel();

        $this->emitSelf('refresh');
    }

    private function getTickets()
    {
        return Ticket::with('event', 'reservations')
            ->whereHas('reservations', fn($query) =>
                $query->whereHas('user', fn($query) => $query->search($this->search))
            )->when($this->event || $this->type,
                fn($query) => $query->whereHas('event',
                    fn($query) => $query->whereId($this->event)->orWhere('type_id', $this->type)
                )
            )->user()->paginate(12);
    }
}
