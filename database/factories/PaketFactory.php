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
        $kecepatan = ['10','30','50','100','200'];
        return [
            'nama_paket' => fake()->words(3, true) . ' ' . random_int(1,5),
            'kecepatan' => $kecepatan[random_int(0,4)],
            'harga' => random_int(10,50)*10000,
            'ket' => fake()->sentence(),
        ];
    }
}
