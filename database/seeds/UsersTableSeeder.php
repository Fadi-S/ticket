<?php

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            "name" => "Administrator",
            "username" => "admin",
            "email" => "admin@alsharobim.com",
            "password" => "123456",
            "email_verified_at" => Carbon::now(),
        ]);

        $user->assignRole('super-admin');
    }
}
