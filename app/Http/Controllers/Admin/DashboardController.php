<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User\User;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        return view("index", [
            'users' => User::role("user")->count(),
            'events' => Event::count(),
            'maxReservations' => config('settings.max_reservations_per_month'),
        ]);
    }

    public function showLogs()
    {
        $logs = Activity::latest()->paginate(100);

        return view("logs", compact('logs'));
    }

}
