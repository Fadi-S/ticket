<?php

use App\Models\EventType;
use App\Models\Kiahk;
use App\Models\Mass;
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
            'model' => Mass::class,
        ]);

        EventType::create([
            "name" => "Kiahk",
            "arabic_name" => "كيهك",
            'model' => Kiahk::class,
        ]);
    }
}
