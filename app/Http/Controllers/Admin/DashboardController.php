<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Baskha;
use App\Models\BaskhaOccasion;
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
            $currentEvents = Event::where([
                ['start', '<', now()],
                ['end', '>', now()]
            ])->get();
        }

        return view("index", [
            'users' => $user->isAdmin() ? User::count() : 0,
            'verified_users' => $user->isAdmin() ? User::verified()->count() : 0,
//            'massTickets' => __(':number of :from left', ['number' => $num->format($tickets->mass()), 'from' => $num->format(Mass::maxReservations())]),
            'baskhaTickets' => __(':number of :from left', ['number' => $num->format($tickets->baskha()), 'from' => $num->format(Baskha::maxReservations())]),
            'baskhaOccasionTickets' => __(':number of :from left', ['number' => $num->format($tickets->baskhaOccasion()), 'from' => $num->format(BaskhaOccasion::maxReservations())]),
            'user' => $user,
            'currentEvents' => $currentEvents ?? null,
        ]);
    }

    public function showLogs()
    {
        $this->authorize('viewActivityLog');

        $logs = Activity::latest()->paginate(10);

        return view("logs", compact('logs'));
    }

}
