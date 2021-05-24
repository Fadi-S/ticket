<?php

use App\Models\Baskha;
use App\Models\BaskhaOccasion;
use App\Models\EventType;
use App\Models\Kiahk;
use App\Models\Mass;
use App\Models\Vesper;
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

        EventType::create([
            "name" => "Baskha",
            "arabic_name" => "بصخة",
            'model' => Baskha::class,
        ]);

        EventType::create([
            "name" => "Vesper",
            "arabic_name" => "عشية",
            'model' => Vesper::class,
        ]);

        EventType::create([
            "name" => "Holly Week",
            "arabic_name" => "اسبوع الالام",
            'model' => BaskhaOccasion::class,
        ]);

        EventType::create([
            "name" => "Mass Open",
            "arabic_name" => "قداس مفتوح",
            'model' => BaskhaOccasion::class,
        ]);
    }
}
