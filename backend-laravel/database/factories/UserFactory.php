<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_ETUDIANT, User::ROLE_PROF];
        $sexes = ['M', 'F'];
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'status' => rand(0, 1),
            'email' => $this->faker->unique()->safeEmail(),
            'roles' => $roles[array_rand($roles, 1)],
            'city' => $this->faker->city(),
            'code_postal' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'sexe' => $sexes[array_rand($sexes, 1)],
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'activation_token' => Str::random(40),
            'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
