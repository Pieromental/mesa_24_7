<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mesa;
use App\Models\Comensal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha' => $this->faker->dateTimeBetween('now', '+7 days')->format('Y-m-d'),
            'hora' => $this->faker->time('H:i'),
            'numero_de_personas' => $this->faker->numberBetween(1, 6),
            'comensal_id' => Comensal::inRandomOrder()->first()?->id,
            'mesa_id' => Mesa::inRandomOrder()->first()?->id,
        ];
    }
}
