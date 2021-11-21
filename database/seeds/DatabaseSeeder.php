<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EventTypesSeeder::class);
        $this->call(LocationsSeeder::class);
        $this->call(ChurchSeeder::class);
        $this->call(ConditionsSeeder::class);
    }
}
