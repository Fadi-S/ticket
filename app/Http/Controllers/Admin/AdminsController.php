<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;

class AdminsController extends Controller
{

    public function index()
    {
        $admins = User::whereHas("roles",
            fn ($query) => $query->where("name", "<>", "user")
        )->paginate(10);

        return view("admins.index", compact('admins'));
    }

    public function show(User $admin)
    {
        return view("admins.show", compact('admin'));
    }

    public function destroy(User $admin)
    {
        if($admin->delete())
            flash()->success("Deleted admin Successfully");
        else
            flash()->error("Error deleting admin");

        return redirect("/admins");
    }
}
