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
            'name' => $name = $this->faker->name(),
            'arabic_name' => "اسم العربي ثالث",
            'email' => $this->faker->unique()->safeEmail(),
            'username' => User::makeSlug($name),
            /*'national_id' => rand(2, 3)
                . $this->faker->randomNumber(6)
                . $this->faker->randomNumber(7),*/
            'phone' => '01'
                . $this->faker->randomNumber(3)
                . $this->faker->randomNumber(6),
            'verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }
}
