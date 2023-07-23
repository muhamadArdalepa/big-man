<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        return [
            'nama' => fake()->firstName() . ' ' . fake()->lastName(),
            'role' => fake()->randomElement([1, 2, 3]),
            'email' => fake()->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Mengenkripsi password dengan bcrypt
            'kota_id' => function () {
                return \App\Models\Kota::inRandomOrder()->first()->id; // Mengambil ID kota secara acak dari model Kota
            },
            'no_telp' => fake()->numerify('628##########'),
            'foto_profil' => 'dummy.png',
            'nik' => fake()->numerify('################'),
            'poin' => fake()->numberBetween(25, 1000),
            'created_at' => now(),
            'updated_at' => now(),
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
