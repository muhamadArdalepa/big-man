<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimAnggota>
 */
class TimAnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tim_id' => function () {
                return \App\Models\Tim::inRandomOrder()->first()->id; // Mengambil ID wilayah secara acak dari model wilayah
            },
            'user_id' => function () {
                $penerima = \App\Models\User::factory()->create(['role' => 2]);
                return $penerima->id;
            },
        ];
    }
}
