<?php

namespace Tests\Feature;

use App\Enums\Diplome;
use App\Enums\StatutDemande;
use App\Models\Demande;
use App\Models\Etudiant;
use App\Models\TypeDemande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DemandeTest extends TestCase
{
    use RefreshDatabase;

    private User $agent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agent = User::factory()->create();
    }

    public function test_guest_cannot_access_demandes(): void
    {
        $this->get(route('demandes.index'))->assertRedirect(route('login'));
    }

    public function test_new_demande_gets_number_document_checklist_and_history(): void
    {
        $type = TypeDemande::factory()->create();
        $type->piecesRequises()->createMany([['libelle' => 'Copie CIN'], ['libelle' => 'Acte de naissance']]);
        $etudiant = Etudiant::factory()->create();

        $response = $this->actingAs($this->agent)->post(route('demandes.store'), [
            'type_demande_id' => $type->id,
            'etudiant_id' => $etudiant->id,
            'niveau' => 'L3',
            'annee_universitaire' => '2025-2026',
        ]);

        $demande = Demande::sole();
        $response->assertRedirect(route('demandes.show', $demande));
        $this->assertSame('DEM-'.now()->year.'-00001', $demande->numero);
        $this->assertSame(StatutDemande::Enregistree, $demande->statut);
        $this->assertTrue($demande->creePar->is($this->agent));
        $this->assertCount(2, $demande->piecesFournies);
        $this->assertCount(1, $demande->historique);
        $this->actingAs($this->agent)->get(route('demandes.show', $demande))->assertOk()->assertSee('Copie CIN');
    }

    public function test_demande_requires_type_and_etudiant(): void
    {
        $this->actingAs($this->agent)->post(route('demandes.store'), [])
            ->assertSessionHasErrors(['type_demande_id', 'etudiant_id']);

        $this->assertDatabaseEmpty('demandes');
    }

    public function test_payment_must_cover_the_applicable_tarif_before_verification(): void
    {
        $type = TypeDemande::factory()->create();
        $type->tarifs()->create(['niveau_diplome' => null, 'montant' => 1000, 'date_debut' => '2020-01-01']);
        $type->tarifs()->create(['niveau_diplome' => Diplome::Dtss, 'montant' => 5000, 'date_debut' => '2020-01-01']);
        $demande = Demande::factory()->for($type)->for(Etudiant::factory()->state(['diplome_vise' => Diplome::Dtss]))->create();

        $this->payer($demande, 'R-1', 1000);
        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), ['statut' => 'PAIEMENT_VERIFIE'])
            ->assertSessionHas('error', 'Le paiement enregistré ne couvre pas le montant attendu.');
        $this->assertSame(StatutDemande::Enregistree, $demande->fresh()->statut);

        $this->payer($demande, 'R-2', 4000);
        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), ['statut' => 'PAIEMENT_VERIFIE'])
            ->assertSessionHasNoErrors();
        $this->assertSame(StatutDemande::PaiementVerifie, $demande->fresh()->statut);
        $this->assertSame(5000, $demande->paiements()->first()->montant_attendu);
    }

    public function test_same_payment_receipt_cannot_be_used_twice(): void
    {
        $premiere = Demande::factory()->create();
        $seconde = Demande::factory()->create();

        $this->payer($premiere, 'R-42', 1500)->assertSessionHasNoErrors();
        $this->payer($seconde, 'R-42', 1500)
            ->assertSessionHasErrorsIn('paiement', ['numero' => 'Ce justificatif a déjà été utilisé pour un autre paiement.']);

        $this->assertCount(0, $seconde->paiements);
    }

    public function test_demande_is_recevable_only_when_mandatory_pieces_are_provided(): void
    {
        $type = TypeDemande::factory()->create();
        $type->piecesRequises()->create(['libelle' => 'Copie CIN', 'obligatoire' => true]);
        $type->piecesRequises()->create(['libelle' => 'Photo', 'obligatoire' => false]);
        $demande = Demande::factory()->for($type)->create();

        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), ['statut' => 'RECEVABLE'])
            ->assertSessionHas('error');
        $this->assertSame(StatutDemande::Enregistree, $demande->fresh()->statut);

        $cin = $demande->piecesFournies()->whereRelation('pieceRequise', 'libelle', 'Copie CIN')->sole();
        $this->actingAs($this->agent)->put(route('pieces-fournies.update', $cin), ['fournie' => '1']);
        $this->assertTrue($cin->fresh()->fournie);
        $this->assertNotNull($cin->fresh()->date_fourniture);

        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), ['statut' => 'RECEVABLE']);
        $this->assertSame(StatutDemande::Recevable, $demande->fresh()->statut);
    }

    public function test_assigning_a_demande_requires_an_agent_and_records_history(): void
    {
        $demande = Demande::factory()->create();

        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), ['statut' => 'AFFECTEE'])
            ->assertSessionHasErrorsIn('statut', ['agent_id' => "Choisissez l'agent à qui affecter la demande."]);

        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), [
            'statut' => 'AFFECTEE',
            'agent_id' => $this->agent->id,
            'commentaire' => 'Urgent',
        ])->assertSessionHasNoErrors();

        $demande->refresh();
        $this->assertSame(StatutDemande::Affectee, $demande->statut);
        $this->assertTrue($demande->agent->is($this->agent));
        $this->assertDatabaseHas('historique_statut', [
            'demande_id' => $demande->id,
            'ancien_statut' => 'ENREGISTREE',
            'nouveau_statut' => 'AFFECTEE',
            'utilisateur_id' => $this->agent->id,
            'commentaire' => 'Urgent',
        ]);
    }

    public function test_refusal_requires_a_reason(): void
    {
        $demande = Demande::factory()->create();

        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), ['statut' => 'REFUSEE'])
            ->assertSessionHasErrorsIn('statut', ['commentaire' => 'Indiquez le motif du refus.']);

        $this->assertSame(StatutDemande::Enregistree, $demande->fresh()->statut);
    }

    public function test_delivery_sets_the_delivery_date(): void
    {
        $demande = Demande::factory()->create();

        $this->actingAs($this->agent)->put(route('demandes.statut', $demande), ['statut' => 'LIVREE']);

        $this->assertNotNull($demande->fresh()->date_livraison);
    }

    public function test_only_a_demande_still_enregistree_can_be_deleted(): void
    {
        $enregistree = Demande::factory()->create();
        $enCours = Demande::factory()->create(['statut' => StatutDemande::EnTraitement]);

        $this->actingAs($this->agent)->delete(route('demandes.destroy', $enCours))->assertSessionHas('error');
        $this->assertModelExists($enCours);

        $this->actingAs($this->agent)->delete(route('demandes.destroy', $enregistree))->assertRedirect(route('demandes.index'));
        $this->assertModelMissing($enregistree);
    }

    public function test_demandes_can_be_filtered_by_student_name(): void
    {
        Demande::factory()->for(Etudiant::factory()->state(['nom' => 'RAKOTO']))->create();
        Demande::factory()->for(Etudiant::factory()->state(['nom' => 'RABE']))->create();

        $this->actingAs($this->agent)->get(route('demandes.index', ['q' => 'RAKOTO']))
            ->assertOk()
            ->assertSee('RAKOTO')
            ->assertDontSee('RABE');
    }

    private function payer(Demande $demande, string $numero, int $montant): TestResponse
    {
        return $this->actingAs($this->agent)->post(route('demandes.paiements.store', $demande), [
            'type_justificatif' => 'RECU_IST',
            'numero' => $numero,
            'annee' => 2026,
            'montant_paye' => $montant,
        ]);
    }
}
