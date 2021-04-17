<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;

class UsersController extends Controller
{

    public function show(User $user=null)
    {
        $user ??= auth()->user();

        $this->authorize('view', $user);

        $tickets = $user->reservedTickets()->with('event')->paginate(15);

        return view("users.show", compact('user', 'tickets'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if($user->delete())
            flash()->success("Deleted User Successfully");
        else
            flash()->error("Error deleting user");

        return redirect("/users");
    }
}
