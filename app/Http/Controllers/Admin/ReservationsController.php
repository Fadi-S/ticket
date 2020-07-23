<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\Event\Event;
use App\Models\Reservation\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{

    use ReservationRepository;

    public function create()
    {
        return view("reservations.create");
    }

    public function store(ReservationRequest $request)
    {
        $reservation = $this->makeReservation($request);

        if($reservation != null)
            flash()->success("Reservation made successfully");
        else
            flash()->error("Couldn't make reservation");

        return redirect("reservations/create");
    }

    public function show(Reservation $reservation)
    {
        return view("reservations.show", compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $users = $this->allUsers();

        return view("reservations.edit", compact('reservation', 'users'));
    }

    public function update(Request $request, Reservation $reservation)
    {

        return redirect("reservations/$reservation->id/edit");
    }

    public function destroy(Reservation $reservation)
    {

        return redirect("reservations");
    }
}
