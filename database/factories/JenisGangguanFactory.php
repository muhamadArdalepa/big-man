<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisGangguan>
 */
class JenisGangguanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama_gangguan' => $this->faker->sentence(3, true),
            'ket' => $this->faker->sentence,
        ];
    }
}
