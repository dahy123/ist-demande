<?php

namespace Database\Factories;

use App\Models\Ecole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ecole>
 */
class EcoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('E???'),
            'nom' => 'École '.fake()->words(3, true),
        ];
    }
}
