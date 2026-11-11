<?php

namespace Database\Factories;

use App\Enums\Diplome;
use App\Models\Attestation;
use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attestation>
 */
class AttestationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'numero' => fake()->unique()->numerify('ATT-####'),
            'etudiant_id' => Etudiant::factory(),
            'diplome' => Diplome::Dts,
        ];
    }
}
