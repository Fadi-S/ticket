<?php

namespace App\Http\Livewire;

use App\Exports\EventReservationsExport;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Period;
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
    public $old = false;

    public bool $pdfRendered = false;
    public array $users = [];

    protected $queryString = [
        'event' => ['except' => 0],
        'old' => ['except' => false],
        'type' => ['except' => 0],
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function getEventModelProperty() { return Event::findOrFail($this->event); }

    public function getTypeModelProperty() { return EventType::find($this->type); }

    public function mount()
    {
        isset($this->event) && Event::findOrFail($this->event);
    }

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
            'deacons' => $this->getDeaconTickets()->paginate(10),
        ])->layout('components.master');
    }

    public function cancelReservation(Reservation $reservation)
    {
        if(auth()->user()->can('reservations.bypass') || (!$reservation->ticket->event->hasPassed() && $reservation->of(auth()->user())))
            $reservation->cancel();
    }

    public function cancel(Ticket $ticket)
    {
        if(auth()->user()->can('reservations.bypass') || (!$ticket->event->hasPassed() && $ticket->of(auth()->user())))
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
        return $this->getTicketsByRole();
    }

    private function getDeaconTickets()
    {
        return $this->getTicketsByRole(true);
    }

    private function getTicketsByRole($deacons=false)
    {
        $userFunction = fn($query) => $query
            ->when(auth()->user()->can('tickets.view') && $this->search,
                fn($query) => $query->searchDatabase($this->search)
            );

        return Ticket::with('event', 'reservedBy')
            ->with(['reservations.user' => $userFunction])
            ->whereHas('reservations',
                function($query) use ($deacons, $userFunction) {
                    $query->where('is_deacon', $deacons)->whereHas('user', $userFunction);
                })->whereHas('event',
                fn($query) => $query
                    ->when(!$this->old, fn($query) => $query->upcoming())
                    ->when($this->event || $this->type,
                        fn($query) => $query->where(function ($query) {
                            $query->where('id', $this->event)->orWhere('type_id', $this->type);
                        })
                    )
            )->user()
            ->orderBy('reserved_at', 'DESC');
    }

}
