<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Models\User\User;
use Carbon\Carbon;

trait UserRepository
{

    function getAllUsers($paginate = 10)
    {
        return User::role("user")->paginate($paginate);
    }

    public function generateUsernameFromName($name, $ignoreId, $id=0)
    {
        $username = str_replace(' ', '', $name);
        if($username == "" || preg_match('/[^A-Za-z0-9]/', $username)) {

            $username = rand(100000, 999999);

        }else {
            $username = strtolower($username);

            if($id)
                $username = $username . "$id";
        }

        if($this->isUniqueUsername($username, $ignoreId))
            return $username;

        return $this->generateUsernameFromName($name, $ignoreId, $id+1);
    }

    public function isUniqueUsername($username, $ignoreId)
    {
        return !User::where([["username", $username], ["id", "<>", $ignoreId]])->exists();
    }

    function createUser(UserRequest $request)
    {
        $user = User::create($request->all());

        $user->assignRole("user");

        $user->email_verified_at = Carbon::now();
        $user->save();

        return $user;
    }

    function updateUser(UserRequest $request, User $user)
    {
        $attr = $request->all();

        if($request->password == "")
            $attr = $request->except("password");

        return $user->update($attr);
    }

    function deleteUser(User $user) {
        try {
            return $user->delete();
        } catch (\Exception $e) {
            return null;
        }
    }

}
