<?php

namespace Database\Factories;

use App\Models\TypeDemande;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TypeDemande>
 */
class TypeDemandeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('TYPE_????'),
            'libelle' => fake()->words(2, true),
            'delai_cible_jours' => 7,
        ];
    }
}
