<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Mass;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start' => now(),
            'end' => now()->addHours(2),
            'number_of_places' => 120,
            'deacons_number' => 10,
            'overload' => 0.2,
            'published_at' => now(),
            'type_id' => 1,
        ];
    }
}
