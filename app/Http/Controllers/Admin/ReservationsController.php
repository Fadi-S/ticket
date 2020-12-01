<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\User\User;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{

    public function index()
    {
        $reservations = Reservation::latest('reserved_at')
            ->user()
            ->with('event', 'user')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view("reservations.create");
    }

    public function store(Request $request)
    {
        $event = Event::findOrFail($request->event);
        $event = app($event->type->model)->find($event->id);

        $users = User::whereIn('id', $request->users)->get();

        $users->each->reserveIn($event);

        if(flash()->messages->isEmpty())
            flash()->success("Reservation(s) made successfully");

        return redirect("reservations/create");
    }

    public function edit(Reservation $reservation)
    {
        if(!$reservation->of(auth()->user()))
            $this->authorize('reservations.edit');

        $users = User::addUsernameToName()->pluck("text", "id");

        return view("reservations.edit", compact('reservation', 'users'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if(!$reservation->of(auth()->user()))
            $this->authorize('reservations.edit');

        $request->validate(["event" => "required"]);

        $newReservation = $reservation->changeEventTo($request->event);

        if(!$newReservation)
            return redirect("reservations/$reservation->id/edit");

        flash()->success("Reservation date changed successfully");
        return redirect("reservations/$newReservation->id/edit");
    }

    public function show(Reservation $reservation)
    {
        return view("reservations.show", compact('reservation'));
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->cancel();

        flash()->success("Reservation Canceled");

        return back();
    }
}
