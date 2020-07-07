<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $users = User::role("user")->count();


        return view("index", compact('users'));
    }

}
