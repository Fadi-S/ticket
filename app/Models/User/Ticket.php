<?php

namespace App\Models\User;

use App\Models\Baskha;
use App\Models\BaskhaOccasion;
use App\Models\Kiahk;
use App\Models\Mass;
use App\Models\Period;
use App\Models\Vesper;
use Carbon\Carbon;

class Ticket
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function reservationsPerPeriod($typeId, $maxReservations, Period $period=null) : int
    {
        $period ??= Period::current();

        $days = $period->start->diffInDays($period->end);

        return $this->calculateReservationsLeft($typeId, $maxReservations, $period->start, $days);
    }

    public function mass($month=null)
    {
        $period = Period::current($month);
        if($period) {
            return $this->reservationsPerPeriod(Mass::$type, Mass::maxReservations(), $period);
        }

        $start = ($month ?? now())->startOfMonth();

        $left = $this->calculateReservationsLeft(Mass::$type, Mass::maxReservations(), $start);

        return $left >= 0 ? $left : 0;
    }

    public function kiahk($date=null)
    {
        $period = Period::current($date);
        if($period) {
            return $this->reservationsPerPeriod(Kiahk::$type, Kiahk::maxReservations(), $period);
        }

        $startOfKiahk = $this->currentKiahkStartDate($date);

        $left = $this->calculateReservationsLeft(Kiahk::$type, Kiahk::maxReservations(), $startOfKiahk, 10);

        return $left >= 0 ? $left : 0;
    }

    public function currentKiahkStartDate($date=null)
    {
        $current = $date ?? now();

        $startOfKiahk = Carbon::create(
            $current->year +  (($current->month == 1) ?  -1 : 0),
            12,
            $current->addYears($current->month == 1 ? 0 : 1)->isLeapYear() ? 11 : 10
        );

        $diffDays = abs($startOfKiahk->diffInDays($current));

        while($diffDays > 10) {
            $diffDays -= 10;

            $startOfKiahk = $startOfKiahk->addDays(10);
        }

        return $startOfKiahk;
    }

    public function vesper($date=null)
    {
        $period = Period::current($date);
        if($period) {
            return $this->reservationsPerPeriod(Vesper::$type, Vesper::maxReservations(), $period);
        }

        $start = ($date ?? now())->startOfMonth();

        $left = $this->calculateReservationsLeft(Vesper::$type, Vesper::maxReservations(), $start);

        return $left >= 0 ? $left : 0;
    }

    public function baskha($date=null)
    {
        $period = Period::current($date);
        if($period) {
            return $this->reservationsPerPeriod(Baskha::$type, Baskha::maxReservations(), $period);
        }

        $start = Carbon::parse('15th April 2022');

        $left = $this->calculateReservationsLeft(Baskha::$type, Baskha::maxReservations(), $start, 10);

        return $left >= 0 ? $left : 0;
    }

    public function baskhaOccasion($date=null)
    {
        $period = Period::current($date);
        if($period) {
            return $this->reservationsPerPeriod(BaskhaOccasion::$type, BaskhaOccasion::maxReservations(), $period);
        }

        $start = Carbon::parse('15th April 2022');

        $left = $this->calculateReservationsLeft(BaskhaOccasion::$type, BaskhaOccasion::maxReservations(), $start, 10);

        return $left >= 0 ? $left : 0;
    }

    private function calculateReservationsLeft($typeId, $maxReservations, $date, $period=null) : int
    {
        $endDate = $period ? $date->copy()->addDays($period) : $date->copy()->addMonth();



        return $maxReservations -
            $this->user
                ->reservations()
                ->where('is_exception', false)
                ->whereHas('ticket', fn ($query) =>
                $query->whereHas('event', fn($query) =>

                $query->where('type_id', $typeId)
                    ->where('start', '>=', $date->format('Y-m-d'))
                    ->where('start', '<=', $endDate->format('Y-m-d'))

                ))->count();
    }
}
