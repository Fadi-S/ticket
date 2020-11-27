<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;

class UsersController extends Controller
{

    public function index()
    {
        $users = User::paginate(10);

        return view("users.index", compact('users'));
    }

    public function show(User $user)
    {
        return view("users.show", compact('user'));
    }

    public function destroy(User $user)
    {
        if($user->delete())
            flash()->success("Deleted User Successfully");
        else
            flash()->error("Error deleting user");

        return redirect("/users");
    }
}
