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
}
