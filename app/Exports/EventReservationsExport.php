<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventReservationsExport implements FromView, WithStyles, ShouldAutoSize
{
    private Event $event;

    public function __construct($event)
    {
        if(is_numeric($event))
            $event = Event::with('tickets.reservations.user')->find($event);

        $this->event = $event;
    }

    private function getUsers()
    {
        $tickets = $this->event->tickets;

        $males = collect();
        $females = collect();
        $deacons = collect();

        foreach ($tickets as $ticket) {
            foreach ($ticket->reservations as $reservation) {
                $user = $reservation->user;

                if($reservation->isDeacon())
                    $deacons->push($user);

                else if($user->gender == 1)
                    $males->push($user);

                else
                    $females->push($user);

            }
        }

        $males = $males->sortBy('arabic_name');
        $females = $females->sortBy('arabic_name');
        $deacons = $deacons->sortBy('arabic_name');

        return [
            'males' => $males,
            'females' => $females,
            'deacons' => $deacons,
        ];
    }

    public function view(): View
    {
        $users = $this->getUsers();

        return view('exports.reservations', [
            'usersGroup' => $users,
            'event' => $this->event,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A:C')
            ->getAlignment()
            ->setHorizontal('center');

        $sheet->getStyle('A:C')
            ->getFont()->setSize(16);

        $sheet->getStyle('1:2')
            ->getFont()
            ->setSize(14);

        $sheet->getStyle('1')
            ->getFont()
            ->setBold(true);
    }
}
