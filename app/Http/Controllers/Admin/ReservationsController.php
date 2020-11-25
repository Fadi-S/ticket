<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\Event\Event;
use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ReservationsController extends Controller
{

    use ReservationRepository;

    public function create()
    {
        return view("reservations.create");
    }

    public function store(ReservationRequest $request)
    {
        $messageBag = new MessageBag();
        $reservation = $this->makeReservation($request, $messageBag);

        if($reservation != null)
            flash()->success("Reservation made successfully");
        else
            flash()->error("Couldn't make reservation");

        return redirect("reservations/create")->withErrors($messageBag);
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

    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $messageBag = new MessageBag();

        if($this->editReservation($request, $reservation, $messageBag))
            flash()->success("Reservation edited successfully");
        else
            flash()->error("Couldn't edit this reservation");

        return redirect("reservations/$reservation->id/edit")->withErrors($messageBag);
    }

    public function destroy(Reservation $reservation)
    {
        $this->delete($reservation);

        flash()->success("Reservation Deleted");

        return redirect("masses/" . $reservation->event->id);
    }
}
