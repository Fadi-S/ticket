<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use UserRepository;

    public function index()
    {
        $users = $this->getAllUsers();
        return view("users.index", compact('users'));
    }

    public function create()
    {
        return view("users.create");
    }

    public function store(UserRequest $request)
    {
        if($this->createUser($request))
            flash()->success("User Created Successfully");
        else
            flash()->error("Error creating user");

        return redirect("/users/create");
    }

    public function show(User $user)
    {
        return view("users.show", compact('user'));
    }

    public function edit(User $user)
    {
        return view("users.edit", compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        if($this->updateUser($request, $user))
            flash()->success("User Edited Successfully");
        else
            flash()->error("Error editing user");

        return redirect("/users/$user->username/edit");
    }

    public function destroy(User $user)
    {
        if($this->deleteUser($user))
            flash()->success("Deleted User Successfully");
        else
            flash()->error("Error deleting user");

        return redirect("/users");
    }
}
