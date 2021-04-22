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
        'page' => ['except' => 1],
    ];

    public function getEventModelProperty() { return Event::find($this->event); }

    public function getTypeModelProperty() { return EventType::find($this->type); }

    private function getUsers()
    {
        $tickets = $this->getTickets()->get();

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
        $this->pdfRendered = true;

        $this->users = $this->getUsers();

        $this->dispatchBrowserEvent('print');
    }

    public function updatedSearch()
    {
        $this->resetPage();
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
            'tickets' => $this->getTickets()->paginate(10),
            'deacons' => $this->getDeaconTickets()->get(),
        ])->layout('components.master');
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
        return $this->getTicketsByRole('user', 'super-admin', 'agent');
    }

    private function getDeaconTickets()
    {
        return $this->getTicketsByRole('deacon', 'deacon-admin');
    }

    private function getTicketsByRole(...$roles)
    {
        if(!is_array($roles))
            $roles = [$roles];

        $userFunction = fn($query) => $query
            ->role($roles)
            ->when(auth()->user()->can('tickets.view') && $this->search,
                fn($query) => $query->searchDatabase($this->search)
            );

        return Ticket::with('event', 'reservations')
            ->whereHas('reservations',
                function($query) use ($roles, $userFunction) {
                    $query->whereHas('user', $userFunction)
                    ->with(['user' => $userFunction]);
                })->whereHas('event',
                fn($query) => $query
                    ->upcoming()
                    ->when($this->event || $this->type,
                        fn($query) => $query->where(function ($query) {
                            $query->where('id', $this->event)->orWhere('type_id', $this->type);
                        })
                    )
            )->user()
            ->orderBy('reserved_at', 'DESC');
    }

}
