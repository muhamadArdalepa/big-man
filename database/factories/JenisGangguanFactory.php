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
            'nama_gangguan' => $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'ket' => $this->faker->sentence,
        ];
    }
}
