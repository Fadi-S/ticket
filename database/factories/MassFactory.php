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
            'start' => now()->startOfMonth(),
            'end' => now()->startOfMonth()->addHours(2),
            'number_of_places' => 120,
            'type_id' => 1,
        ];
    }
}
