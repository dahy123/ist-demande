<?php

namespace Tests\Feature;

use App\Models\Attestation;
use App\Models\Demande;
use App\Models\Quitus;
use App\Models\User;
use Database\Seeders\ReferentielSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Chaque écran s'affiche avec des données réelles.
 */
class PagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_screen_renders_for_an_administrator(): void
    {
        $this->seed(ReferentielSeeder::class);
        $admin = User::factory()->admin()->create();
        $demande = Demande::factory()->create();
        $quitus = Quitus::factory()->create();
        $attestation = Attestation::factory()->create();

        $pages = [
            route('dashboard'),
            route('demandes.index'), route('demandes.create'), route('demandes.show', $demande), route('demandes.edit', $demande),
            route('etudiants.index'), route('etudiants.create'), route('etudiants.show', $demande->etudiant), route('etudiants.edit', $demande->etudiant),
            route('quitus.index'), route('quitus.create'), route('quitus.show', $quitus), route('quitus.edit', $quitus),
            route('attestations.index'), route('attestations.create'), route('attestations.edit', $attestation),
            route('types-demande.index'), route('types-demande.create'), route('types-demande.show', $demande->typeDemande),
            route('ecoles.index'), route('ecoles.create'),
            route('parcours.index'), route('parcours.create'),
            route('utilisateurs.index'), route('utilisateurs.create'), route('utilisateurs.edit', $admin),
            route('journal.index'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($admin)->get($page)->assertOk();
        }
    }

    public function test_dashboard_counts_demandes(): void
    {
        Demande::factory()->count(3)->create();

        $this->actingAs(User::factory()->create())->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('stats', fn (array $stats) => $stats[0]['value'] === 3);
    }
}
