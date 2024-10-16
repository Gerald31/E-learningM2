<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankingInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'iban' => $this->faker->iban('FR'),
            'user_id' => User::where('roles', User::ROLE_PROF)->get()->unique()->random()->id
        ];
    }
}
