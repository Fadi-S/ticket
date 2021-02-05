<?php

namespace Database\Factories\User;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory {

    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'arabic_name' => "اسم العربي",
            'email' => $this->faker->unique()->safeEmail,
            'username' => User::makeSlug($this->faker->unique()->name),
            'national_id' => rand(2, 3)
                . $this->faker->randomNumber(6)
                . $this->faker->randomNumber(7),
            'phone' => '01'
                . $this->faker->randomNumber(3)
                . $this->faker->randomNumber(6),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}