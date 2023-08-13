<?php

namespace Database\Factories;

use App\Models\Tim;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tim>
 */
class TimFactory extends Factory
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
                $userId = User::where('role', 2)->inRandomOrder()->first()->id;
                while (Tim::where('user_id',$userId)->first() !== null) {
                    $userId = User::where('role', 2)->inRandomOrder()->first()->id;
                }
                return  $userId; 
            },
            'status' => fake()->numberBetween(1, 2),
        ];
    }
}
