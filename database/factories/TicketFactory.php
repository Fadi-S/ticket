<?php

namespace Database\Factories;

use App\Helpers\GenerateRandomString;
use App\Models\Event;
use App\Models\Mass;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'event_id' => Mass::factory(),
            'reserved_by' => auth()->id(),
            'reserved_at' => now(),
            'secret' => (new GenerateRandomString)->handle(),
        ];
    }
}
