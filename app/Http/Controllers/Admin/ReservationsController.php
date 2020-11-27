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
        $users = User::whereIn('id', $request->users)->get();

        foreach($users as $user) {
            $success = $user->reserveIn($event);

            if(! $success)
                flash()->error("Couldn't reserve at this date for $user->name");
        }

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

        $reservation = $reservation->changeEventTo($request->event);

        if(!$reservation) {
            flash()->error("Couldn't reserve at this date");
            return response("reservations");
        }

        flash()->success("Reservation date changed successfully");
        return redirect("reservations/$reservation->id/edit");
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
