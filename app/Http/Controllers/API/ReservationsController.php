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

        $isUser = auth()->user()->isUser();
        $isDeacon = auth()->user()->isDeacon();

        $events = Event::published()
            ->upcoming()
            ->visible()
            ->when($isDeacon, fn($query) => $query->where('description', 'LIKE', 'كنيسة%'))
            ->whereBetween('start', [$start, $end])->get();

        // $events = $events->filter(fn($event) => $event->reservations_left > 0)->values();

        $colors = [
            '#5658e8',
            '#37ad28',
            '#72727d',
            '#adb310',
            '#323236',
        ];

        $cacheDates = $start->format('Y-m-d')  . '.' . $end->format('Y-m-d');


        if($isDeacon)
            $role = 'deacon';
        else if($isUser)
            $role = 'user';
        else
            $role = 'admin';

        return \Cache::remember("events.calendar." . $role . '.' .$cacheDates, 20*60,
            fn() => $events->map(function ($event) use ($colors, $isUser, $isDeacon) {

            if($isDeacon) {
                $left = $event->specific()->deacon_reservations_left;
            }else{
                $left = $event->reservations_left;
            }

            if($isUser)
                $left = $left >= 0 ? $left : 0;

            return [
                'id' => $event->id,
                'title' => __(":name | :number left", [
                    'name' => $event->description,
                    'number' => $left,
                ]),
                'start' => $event->start,
                'end' => $event->end,
                'color' => $colors[$event->type_id - 1]
            ];
        }));
    }

}
