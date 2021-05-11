<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Mass;
use App\Models\Period;
use App\Models\User\User;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $num = app()->make('num');
        $tickets = $user->tickets();

        $only = [];
        if($user->hasFirstNameOnly()) {
            array_push($only, 'user.name');
            array_push($only, 'user.arabic');
        }

        if(!auth()->user()->national_id) {
            array_push($only, 'user.national_id');
        }

        if($user->can('tickets.view')) {
            $currentEvents = Event::where([
                ['start', '<', now()],
                ['end', '>', now()]
            ])->get();
        }

        $period = Period::current();

        return view("index", [
            'users' => $user->isAdmin() ? User::count() : 0,
            'verified_users' => $user->isAdmin() ? User::verified()->count() : 0,
            'massTickets' => __(':number of :from left', ['number' => $num->format($tickets->mass()), 'from' => $num->format(Mass::maxReservations())]),
            'user' => $user,
            'period' => $period,
            'currentEvents' => $currentEvents ?? null,
            'only' => $only,
        ]);
    }

    public function showLogs()
    {
        $this->authorize('viewActivityLog');

        $logs = Activity::latest()->paginate(10);

        return view("logs", compact('logs'));
    }

}
