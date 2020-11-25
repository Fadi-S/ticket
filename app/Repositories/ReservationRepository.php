<?php


namespace App\Repositories;


use App\Http\Requests\ReservationRequest;
use App\Models\Event\Event;
use App\Models\Mass;
use App\Models\Reservation;
use App\Models\User\User;
use App\Settings\Settings;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;

trait ReservationRepository
{

    public function makeReservation(ReservationRequest $request, MessageBag $messageBag)
    {
        $event = Event::findOrFail($request->event);
        $users = $request->users;

        if(!$this->canMakeReservation($event, $users)) {
            $messageBag->add("user_reservation", "A user has already reserved in this event or finished their week reservations");
            return false;
        }

        $reservation = Reservation::create([
            "event_id" => $event->id,
            "reserved_at" => Carbon::now(),
            "secret" => $this->generateRandomString(),
        ]);

        $reservation->users()->sync($users);

        return $reservation;
    }

    public function editReservation(ReservationRequest $request, Reservation $reservation, $messageBag)
    {
        $event = Event::findOrFail($request->event);
        $users = $request->users;

        if(!$this->canMakeReservation($event, $users, $reservation)) {
            $messageBag->add("user_reservation", "A user has already reserved in this event or finished their week reservations");
            return false;
        }

        $edited = $reservation->update([
            "event_id" => $event->id,
            "reserved_at" => Carbon::now(),
        ]);

        $reservation->users()->sync($users);

        return $edited;
    }

    private function canMakeReservation(Event $event, $users, Reservation $ignore=null)
    {
        return
            $this->alreadyReserved($event, $users, $ignore) &&
            $this->reservedFromSoon($event, $users, $ignore);
    }

    private function alreadyReserved(Event $event, $users, Reservation $ignore=null)
    {
        return !$event->reservations()
            ->where("id", "<>", ($ignore == null) ? 0 : $ignore->id)
            ->whereHas("users", function ($query) use($users) {
                $query->whereIn("id", $users);
            })->exists();
    }

    private function reservedFromSoon(Event $event, $users, Reservation $ignore=null)
    {
        $eventTime = $event->start;
        $users = User::whereIn("id", $users)->get();

        $maxReservationPerWeek = config('settings.max_reservations_per_week');
        if($maxReservationPerWeek == null)
            return true;

        $maxReservationPerWeek = $maxReservationPerWeek->value;

        foreach ($users as $user) {
            $isReserved = $user->reservations()
                ->where("id", "<>", ($ignore == null) ? 0 : $ignore->id)
                ->whereHas("event", function ($query) use($eventTime) {
                    $query->whereBetween("start", [$eventTime->copy()->startOfWeek(), $eventTime->copy()->endOfWeek()]);
                })->count() >= $maxReservationPerWeek;

            if($isReserved)
                return false;
        }

        return true;
    }

    private function generateRandomString($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function allUsers()
    {
        return User::select("*", \DB::raw("CONCAT(name,' (@',username,')') as text"))
            ->pluck("text", "id");
    }

    public function delete(Reservation $reservation)
    {
        return $reservation->delete();
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
