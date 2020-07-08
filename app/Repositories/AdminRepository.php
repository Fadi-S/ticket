<?php


namespace App\Repositories;


use App\Http\Requests\AdminRequest;
use App\Models\User\User;

trait AdminRepository
{

    function getAllAdmins($paginate = 100)
    {
        return User::whereHas("roles", function ($query) {
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
        $attr = $request->all();

        if($request->password == "")
            $attr = $request->except("password");

        $admin->syncRoles([$request->role]);

        return $admin->update($attr);
    }

    function deleteAdmin(User $admin) {
        try {
            return $admin->delete();
        } catch (\Exception $e) {
            return null;
        }
    }

}
