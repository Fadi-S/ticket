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
        return view("index", [
            'users' => User::role("user")->count(),
            'events' => Event::count(),
            'massTickets' => auth()->user()->tickets()->mass() . ' of ' . (new Mass)->maxReservations() . ' left',
            'kiahkTickets' => auth()->user()->tickets()->kiahk() . ' of ' . (new Kiahk)->maxReservations() . ' left',
        ]);
    }

    public function showLogs()
    {
        $logs = Activity::latest()->paginate(100);

        return view("logs", compact('logs'));
    }

}
