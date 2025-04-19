<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mesa>
 */
class MesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero_mesa' => $this->faker->unique()->numerify('M###'),
            'capacidad' => $this->faker->numberBetween(2, 8),
            'ubicacion' => $this->faker->randomElement(['Interior', 'Terraza', 'VIP']),
        ];
    }
}
