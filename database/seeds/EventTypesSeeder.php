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
            'max_reservations' => 1,
            'url' => 'masses',
            'plural_name' => 'القداسات',
        ]);

        EventType::create([
            "name" => "Kiahk",
            "arabic_name" => "كيهك",
            'max_reservations' => 1,
            'url' => 'kiahk',
            'plural_name' => 'سهرات كيهك',
        ]);

        EventType::create([
            "name" => "Baskha",
            "arabic_name" => "بصخة",
            'max_reservations' => 1,
            'url' => 'baskha',
            'plural_name' => 'البصخات',
        ]);

        EventType::create([
            "name" => "Vesper",
            "arabic_name" => "عشية",
            'max_reservations' => 1,
            'url' => 'vespers',
            'plural_name' => 'العشيات',
        ]);

        EventType::create([
            "name" => "Holy Week",
            "arabic_name" => "أسبوع الالام",
            'max_reservations' => 1,
            'url' => 'holy-week',
            'plural_name' => 'قداسات أسبوع الألام',
        ]);
    }
}
