<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kiahk;
use App\Models\Mass;
use App\Models\Reservation;
use App\Models\User\User;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        $num = app()->make('num');
        $tickets = auth()->user()->tickets();

        return view("index", [
            'users' => User::role("user")->count(),
            'events' => Event::upcoming()->count(),
            'massTickets' => __(':number of :from left', ['number' => $num->format($tickets->mass()), 'from' => $num->format(Mass::maxReservations())]),
            'kiahkTickets' => __(':number of :from left', ['number' => $num->format($tickets->kiahk()), 'from' => $num->format(Kiahk::maxReservations())]),
        ]);
    }

    public function showLogs()
    {
        $logs = Activity::latest()->paginate(10);

        return view("logs", compact('logs'));
    }

}
