<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'login' => $this->faker->unique()->userName(),
            'name' => $this->faker->name(),
            'cpf' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('123456'),
        ];
    }
}
