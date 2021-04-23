<?php

namespace App\Http\Livewire;

use App\Exports\EventReservationsExport;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Reservation;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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

    public function export()
    {
        $file = $this->eventModel->description
            . ' - ' . now()->format('Y-m-d')
            . '.xlsx';

        $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
        $file = mb_ereg_replace("([\.]{2,})", '', $file);

        return Excel::download(
            new EventReservationsExport($this->event),
            $file
        );
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

    public function toggleEvent()
    {
        if(!$this->event)
            return;

        $event = Event::find($this->event);

        $event->hidden_at = $event->hidden_at ? null : now();
        $event->save();
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
