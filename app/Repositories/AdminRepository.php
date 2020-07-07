<?php


namespace App\Repositories;


use App\Http\Requests\AdminRequest;
use App\Models\User\User;

trait AdminRepository
{

    function getAllAdmins($paginate = 100)
    {
        return User::with("roles", function ($query) {
            $query->where("name", "<>", "user");
        })->paginate($paginate);
    }

    function createAdmin(AdminRequest $request)
    {
        $admin = User::create($request->all());

        $admin->assignRole($request->role);

        return $admin;
    }

    function updateAdmin(AdminRequest $request, User $admin)
    {
        return $admin->update($request->all());
    }

    function deleteAdmin(User $admin) {
        try {
            return $admin->delete();
        } catch (\Exception $e) {
            return null;
        }
    }

}
