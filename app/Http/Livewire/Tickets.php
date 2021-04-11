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

    public bool $pdfRendered = false;
    public array $users = [];

    protected $queryString = [
        'event' => ['except' => 0],
        'type' => ['except' => 0],
        'search' => ['except' => ''],
        'page',
    ];

    public function getEventModelProperty() { return Event::find($this->event); }

    public function getTypeModelProperty() { return EventType::find($this->type); }

    private function getUsers()
    {
        $tickets = $this->getTickets();

        $users = collect();

        foreach ($tickets as $ticket) {
            foreach ($ticket->reservations as $reservation)
                $users->push($reservation->user);
        }

        $genders = $users->groupBy(fn ($user) => $user->gender);

        $males = collect(isset($genders[1]) ? $genders[1] : [])
            ->sortBy(fn($user) => $user->locale_name);

        $females = collect(isset($genders[0]) ? $genders[0] : [])
            ->sortBy(fn($user) => $user->locale_name);

        return [
            'females' => $females,
            'males' => $males,
        ];
    }

    public function export()
    {
        if($this->pdfRendered) {
            $this->dispatchBrowserEvent('print');

            return;
        }

        $this->pdfRendered = true;

        $this->users = $this->getUsers();

        $this->dispatchBrowserEvent('print');
    }

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
            'tickets' => $this->getTickets()
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
            ->whereHas('reservations',
                function($query) {
                if(auth()->user()->can('tickets.view') && $this->search) {
                    $query->whereHas('user',
                        fn($query) => $query->searchDatabase($this->search));
                }
            })->whereHas('event',
                    fn($query) => $query
                        ->upcoming()
                        ->when($this->event || $this->type,
                            fn($query) => $query->where('id', $this->event)->orWhere('type_id', $this->type)
                        )
            )->user()
            ->orderBy('reserved_at', 'DESC')
            ->get();
    }
}
