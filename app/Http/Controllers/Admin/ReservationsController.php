<?php

namespace App\Http\Controllers\Admin;

use App\Events\TicketReserved;
use App\Helpers\GenerateRandomString;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{

    public function index()
    {
        $reservations = Reservation::with('ticket.event', 'user')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $users = User::hasFriends()->addUsernameToName()->pluck("text", "id");

        return view("reservations.create", compact('users'));
    }

    public function store(Request $request)
    {
        $event = Event::findOrFail($request->event)->specific();

        $users = User::whereIn('id', $request->users)->get();

        if(!auth()->user()->isAdmin()) {
            if ($users->count() > $event->reservations_left) {
                flash()->error('There is not enough space');

                return redirect("reservations/create");
            }
        }

        $users->each(function ($user) use($event) {
            $output = $user->canReserveIn($event);

            if(is_null($output)) {
                flash()->error("Something went wrong for $user->name");

                return;
            }

            if($output->hasMessage())
                flash()->error($output->message());
        });

        if(flash()->messages->isNotEmpty())
            return redirect("reservations/create");

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'reserved_at' => Carbon::now(),
            'reserved_by' => \Auth::id(),
            'secret' => (new GenerateRandomString)->handle(),
        ]);

        TicketReserved::dispatch();

        $users->each->reserveIn($ticket);

        if(flash()->messages->isEmpty()) {
            flash()->success("Reservation(s) made successfully");
            return redirect("tickets?event=$event->id");
        }

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
