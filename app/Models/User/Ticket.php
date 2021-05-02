<?php

namespace App\Models\User;

use App\Models\Baskha;
use App\Models\BaskhaOccasion;
use App\Models\Kiahk;
use App\Models\Mass;
use App\Models\Vesper;
use Carbon\Carbon;

class Ticket
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function mass(Carbon $month=null)
    {
        $start = ($month ?? now())->startOfMonth();

        if(is_null(Mass::maxReservations()))
            return -1;

        $left = $this->calculateReservationsLeft(Mass::$type, Mass::maxReservations(), $start);

        return $left >= 0 ? $left : 0;
    }

    public function kiahk(Carbon $date=null)
    {
        $startOfKiahk = $this->currentKiahkStartDate($date);

        if(is_null(Kiahk::maxReservations()))
            return -1;

        $left = $this->calculateReservationsLeft(Kiahk::$type, Kiahk::maxReservations(), $startOfKiahk, 10);

        return $left >= 0 ? $left : 0;
    }

    public function currentKiahkStartDate(Carbon $date=null)
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

    public function vesper(Carbon $date=null)
    {
        $start = ($date ?? now())->startOfMonth();

        if(is_null(Vesper::maxReservations()))
            return -1;

        $left = $this->calculateReservationsLeft(Vesper::$type, Vesper::maxReservations(), $start);

        return $left >= 0 ? $left : 0;
    }

    public function baskha(Carbon $date=null)
    {
        $start = Carbon::parse('15th April 2022');

        if(is_null(Baskha::maxReservations()))
            return -1;

        $left = $this->calculateReservationsLeft(Baskha::$type, Baskha::maxReservations(), $start, 10);

        return $left >= 0 ? $left : 0;
    }

    public function baskhaOccasion(Carbon $date=null)
    {
        $start = Carbon::parse('15th April 2022');

        if(is_null(BaskhaOccasion::maxReservations()))
            return -1;

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
                    ->whereBetween('start', [$date, $endDate])

                ))->count();
    }
}
