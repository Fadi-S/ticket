<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Repositories\AdminRepository;

class AdminsController extends Controller
{
    use AdminRepository;

    public function index()
    {
        $admins = $this->getAllAdmins();
        return view("admins.index", compact('admins'));
    }

    public function show(User $admin)
    {
        return view("admins.show", compact('admin'));
    }

    public function destroy(User $admin)
    {
        if($this->deleteAdmin($admin))
            flash()->success("Deleted admin Successfully");
        else
            flash()->error("Error deleting admin");

        return redirect("/admins");
    }
}
