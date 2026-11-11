<?php

namespace Database\Factories;

use App\Enums\Diplome;
use App\Models\Ecole;
use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Etudiant>
 */
class EtudiantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenoms' => fake()->firstName(),
            'date_naissance' => fake()->dateTimeBetween('-30 years', '-18 years'),
            'telephone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'ecole_id' => Ecole::factory(),
            'diplome_vise' => Diplome::Dts,
        ];
    }
}
