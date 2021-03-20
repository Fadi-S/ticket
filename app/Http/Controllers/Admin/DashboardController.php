<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\Event;
use App\Models\Kiahk;
use App\Models\Mass;
use App\Models\Ticket;
use App\Models\User\User;
use App\Notifications\ReservationConfirmed;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        $num = app()->make('num');
        $tickets = auth()->user()->tickets();
        $agents = User::whereHas('roles',
            fn($query) => $query->where('name', 'agent')
        )->with([
            'reservedTickets' => fn($query) => $query->with('event')
                ->whereBetween('reserved_at', [now()->subWeek(), now()])
                ->limit(5)
        ])->get();

        return view("index", [
            'users' => User::role("user")->count(),
            'events' => Event::upcoming()->count(),
            'massTickets' => __(':number of :from left', ['number' => $num->format($tickets->mass()), 'from' => $num->format(Mass::maxReservations())]),
            //'kiahkTickets' => __(':number of :from left', ['number' => $num->format($tickets->kiahk()), 'from' => $num->format(Kiahk::maxReservations())]),
            'agents' => $agents,
        ]);
    }

    public function showLogs()
    {
        $this->authorize('viewActivityLog');

        $logs = Activity::latest()->paginate(10);

        return view("logs", compact('logs'));
    }

}
