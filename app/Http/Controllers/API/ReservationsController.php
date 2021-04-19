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

        $events = Event::published()->whereBetween('start', [$start, $end])->get();

        // $events = $events->filter(fn($event) => $event->reservations_left > 0)->values();

        $colors = [
            '#5658e8',
            '#37ad28',
            '#72727d',
            '#adb310',
            '#323236',
        ];

        return $events->map(fn($event) => [
            'id' => $event->id,
            'title' => __(":name | :number left", [
                'name' => $event->description,
                'number' => $event->reservations_left
            ]),
            'start' => $event->start,
            'end' => $event->end,
            'color' => $colors[$event->type_id - 1]
        ]);
    }

}
