<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\User\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        $users = User::role("user")->count();
        $events = Event::count();

        return view("index", compact('users', 'events'));
    }

    public function showLogs()
    {
        $logs = Activity::latest()->get();

        return view("logs", compact('logs'));
    }

}
