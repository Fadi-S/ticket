<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Mass;
use App\Models\User\User;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $num = app()->make('num');
        $tickets = $user->tickets();

        if($user->can('tickets.view')) {
            $currentEvent = Event::where([
                ['start', '<', now()],
                ['end', '>', now()]
            ])->first();
        }

        return view("index", [
            'users' => $user->isAdmin() ? User::role("user")->count() : 0,
            'massTickets' => __(':number of :from left', ['number' => $num->format($tickets->mass()), 'from' => $num->format(Mass::maxReservations())]),
            //'kiahkTickets' => __(':number of :from left', ['number' => $num->format($tickets->kiahk()), 'from' => $num->format(Kiahk::maxReservations())]),
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
