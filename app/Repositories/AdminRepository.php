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

    function deleteAdmin(User $admin) {
        try {
            return $admin->delete();
        } catch (\Exception $e) {
            return null;
        }
    }

}
