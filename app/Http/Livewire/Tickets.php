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

    public $event;
    public $type;
    public $search = '';

    protected $queryString = [
        'event' => ['except' => 0],
        'type' => ['except' => 0],
        'search' => ['except' => ''],
        'page',
    ];

    public function getEventModelProperty() { return Event::find($this->event); }

    public function getTypeModelProperty() { return EventType::find($this->type); }

    public function getListeners()
    {
        return [
            'echo:tickets,TicketReserved' => '$refresh',

            'cancelReservation' => 'cancelReservation',
        ];
    }

    public function render()
    {
        return view('livewire.tickets', [
            'tickets' => $this->getTickets(),
        ]);
    }

    public function cancelReservation(Reservation $reservation)
    {
        if(!$reservation->ticket->event->hasPassed() && $reservation->of(auth()->user()))
            $reservation->cancel();
    }

    public function cancel(Ticket $ticket)
    {
        if(!$ticket->event->hasPassed() && $ticket->of(auth()->user()))
            $ticket->cancel();
    }

    private function getTickets()
    {
        return Ticket::with('event', 'reservations')
            ->whereHas('reservations', fn($query) =>
                $query->whereHas('user', fn($query) => $query->search($this->search))
            )->whereHas('event',
                    fn($query) => $query
                        ->upcoming()
                        ->when($this->event || $this->type,
                            fn($query) => $query->where('id', $this->event)->orWhere('type_id', $this->type)
                        )
            )->user()
            ->get();
    }
}
