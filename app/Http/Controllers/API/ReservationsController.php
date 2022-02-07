<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{

    function getUsers(Request $request)
    {
        if(! $request->user())
            return ["results" => []];

        return [
            "results" =>
                User::search($request->search)
                    ->when($request->user()->isUser(), fn($query) => $query->hasFriends())
                    ->addUsernameToName()
                    ->get('text', 'id'),
        ];
    }

    function getEvents(Request $request)
    {
        if(! $request->user())
            return [];

        $start = now();
        $end =  Carbon::parse($request->end);

        $isUser = !auth()->user()->can('reservations.bypass');
        $isDeacon = auth()->user()->isDeacon();

        $events = Event::query()
            ->when(auth()->id() != 1, fn($query) => $query->published())
            ->with('type:id,max_reservations,color,max_reservations_for_deacons,has_deacons')
            ->upcoming()
            ->visible()
            ->whereBetween('start', [$start, $end])
            ->selectRaw('events.id, start, end, number_of_places, deacons_number, description, type_id')
            ->withReservationsCount()
            ->get();

        // $events = $events->filter(fn($event) => $event->reservations_left > 0)->values();
        // $cacheDates = $start->format('Y-m-d')  . '.' . $end->format('Y-m-d');


//        if($isDeacon)
//            $role = 'deacon';
//        else if($isUser)
//            $role = 'user';
//        else
//            $role = 'admin';

        $canSeeTickets = auth()->user()->can('tickets.view');

        return $events->map(function ($event) use ($isUser, $isDeacon, $canSeeTickets) {

            $left = $event->reservations_left;
            if($isUser)
                $left = $left >= 0 ? $left : 0;

            $title = __(":name | :number left", [
                'name' => $event->description,
                'number' => $left,
            ]);

            if($event->hasDeacons && ($isDeacon || $canSeeTickets)) {
                $deaconsLeft = $event->deacon_reservations_left;
                if($isUser)
                    $deaconsLeft = $deaconsLeft >= 0 ? $deaconsLeft : 0;

                $title = __(":name | :number left and :deacons deacons", [
                    'name' => $event->description,
                    'number' => $left,
                    'deacons' => $deaconsLeft,
                ]);
            }

            return [
                'id' => $event->id,
                'title' => $title,
                'start' => $event->start,
                'end' => $event->end,
                'color' => $event->type->color,
            ];
        });
    }

}
