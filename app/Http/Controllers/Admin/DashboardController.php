<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Period;
use App\Models\User\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $only = [];
        if($user->hasFirstNameOnly()) {
            array_push($only, 'user.name');
            array_push($only, 'user.arabic');
        }

        if(!auth()->user()->national_id) {
            array_push($only, 'user.national_id');
        }

        $currentEvents = $user->can('tickets.view') ? Event::getCurrent() : [];

        $periods = Period::getLatest();
        $announcements = Announcement::getCurrentForUser();

        $tickets = Cache::tags('ticket.users')->remember('tickets.users.' . $user->id, now()->addMinutes(30),
            function () use($user, $periods) {
                $num = app()->make('num');
                $tickets = [];
                $shownTypes = \Cache::remember('event.types.shown', now()->addHour(),
                    fn() => EventType::shown()->get()
                );

                foreach ($periods as $period) {
                    foreach ($shownTypes as $type) {
                        $tickets[$period->id][$type->id] = __(':number of :from left', ['number' => $num->format($user->tickets()->setPeriod($period)->event($type->id)), 'from' => $num->format($type->max_reservations)]);
                    }
                }

                return $tickets;
            }
        );

        $usersCount = $user->isAdmin() ? Cache::remember('users.count', now()->addMinutes(10),
            fn() => [
                'verified' => User::verified()->count(),
                'all' => User::count(),
            ]
        ) : ['all' => 0, 'verified' => 0];

        return view("index", [
            'users' => $usersCount['all'],
            'verified_users' => $usersCount['verified'],
            'tickets' => $tickets,
            'user' => $user,
            'periods' => $periods,
            'currentEvents' => $currentEvents ?? null,
            'only' => $only,
            'announcements' => $announcements,
        ]);
    }

    public function showLogs()
    {
        $this->authorize('viewActivityLog');

        $logs = Activity::latest()->paginate(10);

        return view("logs", compact('logs'));
    }

    public function adminHackerTrap()
    {
        $time = Cookie::get('hah') ?? 0;

        $replies = collect([
            "Please don't hack me!",
            "Hi! I'm Mr.Robot",
            "Looking for smth?",
            "Okay, you are really trying!",
            "Can you please stop..",
            "Don't be evil :(",
        ]);

        Cookie::queue('hah', $time+1, 24*365);

        $adminAccess = json_decode(Cache::get('admin-access', "[]"));
        array_push($adminAccess, [
            'user_id' => \Auth::id() ?? null,
            'ip' => request()->getClientIp(),
            'client' => request()->header('User-Agent'),
            'time' => now(),
        ]);

        Cache::put('admin-access', json_encode($adminAccess));

        if($replies->count() > $time) {
            $index = $time;
        } else {
            return redirect()->to('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        }

        return $replies->get($index);
    }

}
