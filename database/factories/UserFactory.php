<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        return [
            'nama' => $firstName . ' ' . $lastName,
            'role' => fake()->randomElement([2, 3]),
            'email' => strtolower($firstName.$lastName).'@big.com',
            'password' => bcrypt('password'),
            'kota_id' => function () {
                return \App\Models\Kota::inRandomOrder()->first()->id;
            },
            'no_telp' => fake()->numerify('628##########'),
            'foto_profil' => 'dummy.png',
            'poin' => fake()->numberBetween(25, 1000),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
