<?php

namespace Database\Factories;

use App\Models\Mass;
use Illuminate\Database\Eloquent\Factories\Factory;

class MassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start' => now()->startOfMonth()->addDays(15),
            'end' => now()->startOfMonth()->addDays(15)->addHours(2),
            'number_of_places' => 120,
            'deacons_number' => 10,
            'overload' => 0.2,
            'published_at' => now(),
            'type_id' => 1,
        ];
    }
}
