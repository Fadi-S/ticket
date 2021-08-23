<?php

namespace App\Models\User;

use App\Models\EventType;
use App\Models\Period;

class Ticket
{
    private User $user;
    private Period $period;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function setPeriod(Period $period)
    {
        $this->period = $period;

        return $this;
    }

    public function reservationsPerPeriod($typeId, $maxReservations, Period $period=null) : int
    {
        $period ??= Period::current();

        $left = $this->calculateReservationsLeft($typeId, $maxReservations, $period->start, $period->end);

        return $left >= 0 ? $left : 0;
    }

    public function event($type, $date=null)
    {
        if(is_numeric($type))
            $type = EventType::find($type);

        $period = $this->period ?? Period::current($date);

        if($period) {
            return $this->reservationsPerPeriod($type->id, $type->maxReservationsForUser($this->user), $period);
        }

        return 0;
    }

    private function calculateReservationsLeft($typeId, $maxReservations, $startDate, $endDate) : int
    {
        return $maxReservations -
            $this->user
                ->reservations()
                ->where('is_exception', false)
                ->whereHas('ticket', fn ($query) =>
                $query->whereHas('event', fn($query) =>

                $query->where('type_id', $typeId)
                    ->whereBetween('start', [$startDate->startOfDay(), $endDate->endOfDay()])

                ))->count();
    }
}
