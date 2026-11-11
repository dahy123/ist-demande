<?php

namespace Database\Factories;

use App\Models\Demande;
use App\Models\Etudiant;
use App\Models\TypeDemande;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Demande>
 */
class DemandeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type_demande_id' => TypeDemande::factory(),
            'etudiant_id' => Etudiant::factory(),
            'niveau' => 'L3',
            'annee_universitaire' => '2025-2026',
        ];
    }
}
