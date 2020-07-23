<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mass\Mass;
use App\Repositories\ReservationRepository;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{

    use ReservationRepository;

    function getUsers(Request $request)
    {
        $users = $this->getUsersBySearch($request->search);

        $usersFormatted = [];

        foreach ($users as $id => $text) {
            $usersFormatted[] = [
                "id" => $id,
                "text" => $text,
            ];
        }

        return [
            "results" => $usersFormatted,

        ];

    }

    function getEvents()
    {
        return $this->getFormattedEvents();
    }

}
