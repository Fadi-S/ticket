<?php

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventType::create([
            "name" => "Mass",
            "arabic_name" => "قداس",
        ]);
    }
}
