<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
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

    public function create()
    {
        return view("admins.create");
    }

    public function store(AdminRequest $request)
    {
        if($this->createAdmin($request))
            flash()->success("Admin Created Successfully");
        else
            flash()->error("Error creating admin");

        return redirect("/admins/create");
    }

    public function show(User $admin)
    {
        return view("admins.show", compact('admin'));
    }

    public function edit(User $admin)
    {
        return view("admins.edit", compact('admin'));
    }

    public function update(AdminRequest $request, User $admin)
    {
        if($this->updateAdmin($request, $admin))
            flash()->success("Admin Edited Successfully");
        else
            flash()->error("Error editing admin");

        return redirect("/admins/$admin->username/edit");
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
