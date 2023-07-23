<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemasangan>
 */
class PemasanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                $user = \App\Models\User::factory()->create(['role' => 3]);
                return $user->id;
            },
            'alamat' => fake()->address(),
        ];
    }
}
