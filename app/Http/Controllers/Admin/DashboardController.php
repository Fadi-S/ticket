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
        $user = auth()->user();
        $num = app()->make('num');
        $tickets = $user->tickets();

        if($user->can('viewAgentDetails')) {
            $agents = User::whereHas('roles',
                fn($query) => $query->where('name', 'agent')
            )->with([
                'reservedTickets' => fn($query) => $query->with('event')
                    ->whereBetween('reserved_at', [now()->subWeek(), now()])
                    ->limit(5)
            ])->get();
        }

        if($user->can('tickets.view')) {
            $currentEvent = Event::where([
                ['start', '<', now()],
                ['end', '>', now()]
            ])->first();
        }

        return view("index", [
            'users' => $user->isAdmin() ? User::role("user")->count() : 0,
            'events' => $user->isAdmin() ? Event::upcoming()->count() : 0,
            'massTickets' => __(':number of :from left', ['number' => $num->format($tickets->mass()), 'from' => $num->format(Mass::maxReservations())]),
            //'kiahkTickets' => __(':number of :from left', ['number' => $num->format($tickets->kiahk()), 'from' => $num->format(Kiahk::maxReservations())]),
            'agents' => $agents ?? collect(),
            'user' => $user,
            'currentEvent' => $currentEvent ?? null,
        ]);
    }

    public function showLogs()
    {
        $this->authorize('viewActivityLog');

        $logs = Activity::latest()->paginate(10);

        return view("logs", compact('logs'));
    }

}
