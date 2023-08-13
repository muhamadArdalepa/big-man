<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paket>
 */
class PaketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama_paket' => fake()->word() . ' ' . fake()->randomNumber(),
            'harga' => fake()->randomFloat(2, 10, 100),
            'ket' => fake()->sentence(),
        ];
    }
}
