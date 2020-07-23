<?php


namespace App\Repositories;


use App\Http\Requests\ReservationRequest;
use App\Models\Event\Event;
use App\Models\Mass\Mass;
use App\Models\Reservation\Reservation;
use App\Models\User\User;
use Carbon\Carbon;

trait ReservationRepository
{

    public function makeReservation(ReservationRequest $request)
    {
        $event = Event::findOrFail($request->event);
        $users = $request->users;

        $reservation = Reservation::create([
            "event_id" => $event->id,
            "reserved_at" => Carbon::now(),
            "secret" => "SECRET",
        ]);

        $reservation->users()->sync($users);

        return $reservation;
    }

    function allUsers()
    {
        return User::select("*", \DB::raw("CONCAT(name,' (@',username,')') as text"))
            ->pluck("text", "id");
    }

    function getUsersBySearch($search)
    {

        return User::where("name", "like", "%$search%")
            ->orWhere("username", "like", "%$search%")
            ->orWhere("email", "like", "%$search%")

            ->select("*", \DB::raw("CONCAT(name,' (@',username,')') as text"))
            ->pluck("text", "id");

    }

    function getFormattedEvents()
    {
        $events = Mass::withoutGlobalScope("event_type")->get();

        $eventsFormatted = [];

        foreach ($events as $event)
        {
            $eventsFormatted[] = [
                'id' => $event->id,
                'title' => $event->type->arabic_name,
                'start' => $event->start,
                'end' => $event->end,
            ];
        }

        return $eventsFormatted;
    }

}
