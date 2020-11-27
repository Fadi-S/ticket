<?php

namespace App\Traits;

use App\Helpers\GenerateRandomString;
use App\Models\Event;
use App\Models\Reservation;
use Carbon\Carbon;

trait CanReserveEvents
{

    public function reserveIn(Event $event)
    {
        if($this->alreadyReservedIn($event))
            return false;

        $exception = $this->qualifiesForException($event);

        if(!($exception || !$this->reservedFromSoon($event) || $this->reservedByAdmin()))
            return false;

        $reservation = new Reservation([
            "event_id" => $event->id,
            "reserved_at" => Carbon::now(),
            'is_exception' => $exception,
            'reserved_by' => \Auth::id(),
            "secret" => (new GenerateRandomString)->handle(),
        ]);

        return $this->reservations()->save($reservation);
    }

    public function qualifiesForException(Event $event)
    {
        if(!config('settings.allow_for_exceptions'))
            return false;

        return abs(Carbon::now()->diffInHours($event->start)) <= config('settings.hours_to_allow_for_exception');
    }

    public function reservedByAdmin()
    {
        $user = auth()->user();

        return !$user->hasRole('user') && $user->id != $this->id;
    }

    public function alreadyReservedIn(Event $event)
    {
        return $this->reservations->where('event_id', $event->id)->exists();
    }

    public function reservedFromSoon(Event $event)
    {
        $maxPerMonth = config('settings.max_reservations_per_month');
        if($maxPerMonth == null)
            return false;

        return $this->reservationsFromMonth($event->start)->count() >= $maxPerMonth;
    }

    public function reservationsFromMonth(Carbon $start)
    {
        $start = $start->startOfMonth();

        return $this->reservations()
            ->where('is_exception', false)
            ->whereHas("event",
                fn($query) => $query->whereBetween("start", [$start, $start->copy()->addMonth()])
            );
    }

    public function reservationsLeft()
    {
        return config('settings.max_reservations_per_month')
            - $this->reservations->where('is_exception', false)->count();
    }

}