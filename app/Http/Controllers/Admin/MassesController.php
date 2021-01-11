<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventsRequest;
use App\Models\Mass;
use Carbon\Carbon;

class MassesController extends Controller
{

    public function create()
    {
        $start = Carbon::now();
        return view("events.create", [
            'start' => $start,
            'url' => 'masses'
        ]);
    }

    public function store(EventsRequest $request)
    {
        if(Mass::create($request->validated()))
            flash()->success("Created mass successfully");
        else
            flash()->error("Error creating mass");

        return redirect("/masses/create");
    }

    public function edit(Mass $mass)
    {
        $start = Carbon::now();
        return view("events.edit", [
            'start' => $start,
            'url' => 'masses',
            'event' => $mass,
        ]);
    }

    public function update(Mass $mass, EventsRequest $request)
    {
        if($mass->update($request->validated()))
            flash()->success("Edited mass successfully");
        else
            flash()->error("Error editing mass");

        return redirect("/masses/$mass->id/edit");
    }

    public function index()
    {
        $masses = Mass::orderBy('start')
            ->whereDate('end', '>=', now())
            ->with('tickets.reservations.user')
            ->paginate(10);

        return view("mass.index", compact('masses'));
    }

    public function show(Mass $mass)
    {
        $mass->load('reservations.user');

        return view("mass.show", compact("mass"));
    }

}
