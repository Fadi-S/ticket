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

        return (new Mass)->maxReservations() -
            $this->user
                ->reservations()
                ->where('is_exception', false)
                ->whereHas('event',
                    fn($query) => $query
                        ->where('type_id', 1)
                        ->whereBetween('start', [$start, $start->copy()->addMonth()])
                )->count();
    }

    public function kiahk(Carbon $year=null)
    {
        $start = ($year ?? now())->startOfYear();

        return (new Kiahk)->maxReservations() -
            $this->user
                ->reservations()
                ->where('is_exception', false)
                ->whereHas('event',
                    fn($query) => $query
                        ->where('type_id', 2)
                        ->whereBetween('start', [$start, $start->copy()->addYear()])
                )->count();
    }
}