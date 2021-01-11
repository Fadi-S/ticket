<?php

namespace App\Models\User;

use App\Models\Kiahk;
use App\Models\Mass;
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

        $left = (new Mass)->maxReservations() -
            $this->user
                ->reservations()
                ->where('is_exception', false)
                ->whereHas('ticket', fn ($query) =>
                $query->whereHas('event', fn($query) =>

                $query->where('type_id', 1)
                    ->whereBetween('start', [$start, $start->copy()->addMonth()])

                ))->count();

        return $left >= 0 ? $left : 0;
    }

    public function kiahk(Carbon $date=null)
    {
        $startOfKiahk = $this->currentKiahkStartDate($date);

        $left = (new Kiahk)->maxReservations() -
            $this->user
                ->reservations()
                ->where('is_exception', false)
                ->whereHas('ticket', fn ($query) =>
                    $query->whereHas('event', fn($query) =>

                        $query->where('type_id', 2)
                            ->whereBetween('start', [$startOfKiahk, $startOfKiahk->copy()->addDays(10)])

                    ))->count();

        return $left >= 0 ? $left : 0;
    }

    public function currentKiahkStartDate(Carbon $date=null)
    {
        if(now()->month == 1)
            $startOfKiahk = Carbon::create( now()->year - 1, 12, now()->isLeapYear() ? 11 : 10);
        else
            $startOfKiahk = Carbon::create( now()->year, 12, now()->addYear()->isLeapYear() ? 11 : 10);

        $current = $date ?? now();

        $diffDays = abs($startOfKiahk->diffInDays($current));

        while($diffDays > 10) {
            $diffDays -= 10;

            $startOfKiahk = $startOfKiahk->addDays(10);
        }

        return $startOfKiahk;
    }
}