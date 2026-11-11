<?php

namespace Tests\Feature;

use App\Models\Attestation;
use App\Models\Etudiant;
use App\Models\Quitus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuitusTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_quitus_is_numbered_and_gets_its_six_visas(): void
    {
        $etudiant = Etudiant::factory()->create();

        $this->actingAs(User::factory()->create())->post(route('quitus.store'), [
            'etudiant_id' => $etudiant->id,
            'annee' => 2026,
            'en_retard' => '1',
            'frais_retard' => 15000,
        ])->assertRedirect();

        $quitus = Quitus::sole();
        $this->assertSame('0001/2026', $quitus->numero);
        $this->assertSame(15000, $quitus->frais_retard);
        $this->assertCount(6, $quitus->visas);
        $this->assertFalse($quitus->estComplet());
    }

    public function test_frais_retard_are_reset_when_not_late(): void
    {
        $this->actingAs(User::factory()->create())->post(route('quitus.store'), [
            'etudiant_id' => Etudiant::factory()->create()->id,
            'annee' => 2026,
            'frais_retard' => 15000,
        ]);

        $this->assertSame(0, Quitus::sole()->frais_retard);
    }

    public function test_student_has_only_one_quitus_per_year(): void
    {
        $quitus = Quitus::factory()->create(['annee' => 2026]);

        $this->actingAs(User::factory()->create())->post(route('quitus.store'), [
            'etudiant_id' => $quitus->etudiant_id,
            'annee' => 2026,
        ])->assertSessionHasErrors(['etudiant_id' => 'Cet étudiant a déjà un quitus pour cette année.']);

        $this->assertSame(1, Quitus::count());
    }

    public function test_quitus_is_complete_once_every_service_has_signed(): void
    {
        $user = User::factory()->create();
        $quitus = Quitus::factory()->create();

        foreach ($quitus->visas as $visa) {
            $this->actingAs($user)->put(route('quitus-visas.update', $visa), ['valide' => '1', 'responsable' => 'M. Rabe'])
                ->assertSessionHasNoErrors();
        }

        $quitus->load('visas');
        $this->assertTrue($quitus->estComplet());
        $this->assertNotNull($quitus->visas->first()->date_visa);

        $this->actingAs($user)->put(route('quitus-visas.update', $quitus->visas->first()), ['valide' => '0']);
        $this->assertNull($quitus->visas->first()->fresh()->date_visa);
        $this->assertFalse($quitus->fresh()->estComplet());
    }

    public function test_attestation_number_is_unique(): void
    {
        $existante = Attestation::factory()->create();

        $this->actingAs(User::factory()->create())->post(route('attestations.store'), [
            'numero' => $existante->numero,
            'etudiant_id' => $existante->etudiant_id,
            'diplome' => 'DTS',
            'version' => 'PROVISOIRE',
        ])->assertSessionHasErrors('numero');

        $this->assertSame(1, Attestation::count());
    }
}
