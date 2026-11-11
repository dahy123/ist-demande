<?php

namespace Database\Factories;

use App\Models\Etudiant;
use App\Models\Quitus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quitus>
 */
class QuitusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'annee' => 2026,
            'etudiant_id' => Etudiant::factory(),
        ];
    }
}
