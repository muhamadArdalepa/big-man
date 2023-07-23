<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\JenisGangguan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laporan>
 */
class LaporanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pelapor' => function () {
                $pelapor = User::factory()->create(['role' => 3]);
                return $pelapor->id;
            },
            'penerima' => function () {
                $penerima = User::factory()->create(['role' => 2]);
                return $penerima->id;
            },
            'jenis_gangguan_id' => function () {
                return JenisGangguan::factory()->create()->id;
            },
            'status' => 1,
            'ket' => $this->faker->sentence,
        ];
    }
}