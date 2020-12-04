<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventsRequest;
use App\Models\Kiahk;
use Carbon\Carbon;

class KiahkController extends Controller
{
    public function create()
    {
        $start = Carbon::now();
        return view("events.create", [
            'start' => $start,
            'url' => 'kiahk'
        ]);
    }

    public function store(EventsRequest $request)
    {
        if(Kiahk::create($request->all()))
            flash()->success("Created event successfully");
        else
            flash()->error("Error creating event");

        return redirect("/kiahk/create");
    }

    public function edit(Kiahk $kiahk)
    {
        $start = Carbon::now();
        return view("events.edit", [
            'start' => $start,
            'url' => 'kiahk',
            'event' => $kiahk,
        ]);
    }

    public function update(Kiahk $kiahk, EventsRequest $request)
    {
        if($kiahk->update($request->all()))
            flash()->success("Edited kiahk successfully");
        else
            flash()->error("Error editing kiahk");

        return redirect("/kiahk/$kiahk->id/edit");
    }

    public function index()
    {
        $kiahks = Kiahk::latest()
            ->with('tickets.reservations.user')
            ->paginate(10);

        return view("kiahk.index", compact('kiahks'));
    }

    public function show(Kiahk $kiahk)
    {
        $kiahk->load('reservations.user');

        return view("kiahk.show", compact("kiahk"));
    }
}
