<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use UserRepository;

    public function generateUsername(Request $request)
    {
        return ["username" => $this->generateUsernameFromName($request->name, $request->id)];
    }

    public function checkUsername(Request $request)
    {
        $username = $request->username;

        if(preg_match('/[^A-Za-z0-9.]/', $username))
            return [
                "unique" => false,
                "username" => $this->generateUsernameFromName(
                    preg_match('/[^A-Za-z0-9]/', $request->name) ?  $request->name : "", $request->id
                )
            ];

        if($this->isUniqueUsername($username, $request->id))
            return ["unique" => true, "username" => $username];

        return ["unique" => false, "username" => $this->generateUsernameFromName($request->name, $request->id)];
    }

}
