<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::upcoming()
            ->published()
            ->orderBy('start')
            ->select('id', 'description', 'start', 'end')
            ->simplePaginate(10);

        return ['events' => $events];
    }


    public function reserved()
    {
        $events = Event::upcoming()
            ->published()
            ->whereHas('tickets', function ($query) {
                $query->whereHas('reservations', fn($q) => $q->where('user_id', auth()->id()));
            })->orderBy('start')
            ->select('id', 'description', 'start', 'end')
            ->get();

        return ['events' => $events];
    }
}
