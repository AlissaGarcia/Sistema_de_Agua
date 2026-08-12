<?php

namespace Database\Factories;

use App\Models\Consumidor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Consumidor>
 */
class ConsumidorFactory extends Factory
{
    protected $model = Consumidor::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'endereco' => fake()->address(),
            'numero_medidor' => fake()->unique()->numerify('########'),
            'telefone' => fake()->numerify('##########'),
        ];
    }
}
