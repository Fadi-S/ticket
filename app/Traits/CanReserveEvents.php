<?php

namespace App\Traits;

use App\Helpers\GenerateRandomString;
use App\Models\Reservation;
use Carbon\Carbon;

trait CanReserveEvents
{

    public function reserveIn($event)
    {


        $output = null;

        foreach ($event->conditions() as $condition)
        {
            $output = app($condition)->check($event, $this);

            if($output->shouldContinue())
                continue;

            break;
        }

        if(is_null($output)) {
            flash()->error("Something went wrong for $this->name");

            return false;
        }

        if($output->hasMessage())
            flash()->error($output->message());

        if($output->isDenied())
            return false;

        $reservation = new Reservation(
            array_merge([
            'event_id' => $event->id,
            'reserved_at' => Carbon::now(),
            'reserved_by' => \Auth::id(),
            'secret' => (new GenerateRandomString)->handle(),
        ], $output->body() ?? []));

        $this->reservations()->save($reservation);

        return $reservation;
    }

    public function reservationsFromMonth(Carbon $start)
    {
        $start = $start->startOfMonth();

        return $this->reservations()
            ->where('is_exception', false)
            ->whereHas('event',
                fn($query) => $query->whereBetween('start', [$start, $start->copy()->addMonth()])
            );
    }

}