<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User\User;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    function getUsers(Request $request)
    {
        return [
            "results" =>
                User::search($request->search)
                ->addUsernameToName()
                ->get('text', 'id'),
        ];
    }

    function getEvents()
    {
        return Event::all()->map(fn($event) => [
            'id' => $event->id,
            'title' => $event->type->arabic_name,
            'start' => $event->start,
            'end' => $event->end,
        ]);
    }

}
