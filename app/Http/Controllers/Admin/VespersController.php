<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventsRequest;
use App\Models\Vesper;
use Carbon\Carbon;

class VespersController extends Controller
{
    public function create()
    {
        $start = Carbon::now();
        return view("events.create", [
            'start' => $start,
            'url' => 'vespers'
        ]);
    }

    public function store(EventsRequest $request)
    {
        if(Vesper::create($request->all()))
            flash()->success("Created event successfully");
        else
            flash()->error("Error creating event");

        return redirect("/vespers/create");
    }

    public function edit(Vesper $vesper)
    {
        $start = Carbon::now();
        return view("events.edit", [
            'start' => $start,
            'url' => 'vespers',
            'event' => $vesper,
        ]);
    }

    public function update(Vesper $vesper, EventsRequest $request)
    {
        if($vesper->update($request->all()))
            flash()->success("Edited vesper successfully");
        else
            flash()->error("Error editing vesper");

        return redirect("/vespers/$vesper->id/edit");
    }

    public function index()
    {
        $vespers = Vesper ::orderBy('start')
            ->whereDate('end', '>=', now())
            ->with('tickets.reservations.user')
            ->paginate(10);

        return view("vespers.index", compact('vespers'));
    }

    public function show(Vesper $vesper)
    {
        $vesper->load('reservations.user');

        return view("vespers.show", compact("vesper"));
    }
}
