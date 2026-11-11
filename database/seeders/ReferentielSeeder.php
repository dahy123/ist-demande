<?php

namespace Database\Seeders;

use App\Models\Ecole;
use App\Models\Parcours;
use App\Models\Role;
use App\Models\TypeDemande;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Données de référence de l'IST (reprises de doc/database.sql).
 * Peut être relancé sans créer de doublons.
 */
class ReferentielSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->ecoles();
        $this->roles();
        $this->typesDemande();
        $this->parcours();
    }

    private function ecoles(): void
    {
        foreach ([
            'EGI' => 'École du Génie Industriel',
            'EGMCS' => 'École du Génie du Management, du Commerce et des Services',
            'EGCGN' => 'École du Génie Civil et Naval',
        ] as $code => $nom) {
            Ecole::updateOrCreate(['code' => $code], ['nom' => $nom]);
        }
    }

    private function roles(): void
    {
        foreach ([
            'ADMIN' => 'Administrateur',
            'CAISSIERE' => 'Caissière',
            'SECRETAIRE' => 'Secrétaire',
            'AGENT' => 'Agent traitant',
            'VISA' => 'Agent visa',
            'SCOLARITE' => 'Responsable scolarité',
            'RESPONSABLE' => 'Directeur ou responsable',
        ] as $code => $libelle) {
            Role::updateOrCreate(['code' => $code], ['libelle' => $libelle]);
        }
    }

    private function typesDemande(): void
    {
        $types = [
            'RELEVE' => ['libelle' => 'Relevé de notes', 'tarifs' => [[null, 1500, 15000]]],
            'QUITUS' => ['libelle' => 'Quitus', 'tarifs' => [[null, 0, 15000]], 'pieces' => [
                'Livre final papier signé par l\'encadreur',
                'CD : PowerPoint de soutenance et livre électronique signé',
                'Reçu du fond solidaire',
                'Accusé de réception du livre par l\'entreprise de stage',
            ]],
            'ATTESTATION' => ['libelle' => 'Attestation'],
            'NON_ENGAGEMENT' => ['libelle' => 'Attestation de non engagement'],
            'DIPLOME' => ['libelle' => 'Demande de diplôme', 'tarifs' => [
                ['DTS', 45000, null], ['DTSS', 50000, null], ['INGENIORAT', 60000, null],
            ], 'pieces' => [
                'Demande adressée au DG avec téléphone',
                'Copie de l\'acte de naissance',
                'Photocopie de la CIN',
                'Original de l\'attestation',
                'Reçu du fond solidaire (photocopie)',
                'Attestation de non engagement (diplôme de Bac légalisé)',
                'Reçu du droit de diplôme',
            ]],
            'FOND_SOLIDAIRE' => ['libelle' => 'Fond solidaire', 'tarifs' => [[null, 60000, null]]],
            'LEGALISATION' => ['libelle' => 'Légalisation'],
        ];

        foreach ($types as $code => $definition) {
            $type = TypeDemande::updateOrCreate(['code' => $code], ['libelle' => $definition['libelle']]);

            foreach ($definition['tarifs'] ?? [] as [$diplome, $montant, $montantRetard]) {
                $type->tarifs()->updateOrCreate(
                    ['niveau_diplome' => $diplome, 'date_debut' => '2026-01-01'],
                    ['montant' => $montant, 'montant_retard' => $montantRetard],
                );
            }

            foreach ($definition['pieces'] ?? [] as $libelle) {
                $type->piecesRequises()->firstOrCreate(['libelle' => $libelle]);
            }
        }
    }

    private function parcours(): void
    {
        $tous = 'L1,L2,L3,M1,M2';

        $parcours = [
            ['EGI', 'MEEM', 'Maintenance des Équipements Électromécaniques', 'MME', $tous],
            ['EGI', 'MEFT', 'Maintenance en Équipements Frigorifiques et Thermiques', 'MME', $tous],
            ['EGI', 'MSA', 'Maintenance en Systèmes Automatisés', 'MME', $tous],
            ['EGI', 'SERA', 'Systèmes à Énergies Renouvelables et Alternatives', 'MME', $tous],
            ['EGI', 'MURE', null, 'MME', $tous],
            ['EGI', 'NTE', 'Nouvelle Technologie de l\'Électricité', 'MME', $tous],
            ['EGI', 'TAM', 'Techniques Avancées de Maintenance', 'MME', $tous],
            ['EGI', 'RT', 'Réseaux et Télécommunications', 'TC', $tous],
            ['EGI', 'TIM', 'Technologie de l\'Information et Multimédia', 'TC', $tous],
            ['EGI', 'ADR', 'Administration des Réseaux', 'TC', $tous],
            ['EGI', 'IRM', 'Ingénierie des Réseaux Mobiles', 'TC', 'L3'],
            ['EGI', 'ICE', 'Ingénierie des Communications Électroniques', 'TC', $tous],
            ['EGMCS', 'COM', 'Commerce', 'CS', $tous],
            ['EGMCS', 'TGH', 'Tourisme et Gestion Hôtelière', 'CS', $tous],
            ['EGMCS', 'TCI', 'Transit et Commerce International', 'CS', $tous],
            ['EGMCS', 'DPT', 'Développement de Produits Touristiques', 'CS', 'L3'],
            ['EGMCS', 'TBA', 'Technique Bancaire et Assurance', 'FBA', $tous],
            ['EGMCS', 'GFC', 'Gestion Financière et Comptable', 'FBA', $tous],
            ['EGMCS', 'CGC', 'Conseiller Gestionnaire de Clientèle', 'FBA', $tous],
            ['EGMCS', 'CCA', 'Comptabilité Contrôle et Audit', 'FBA', 'L3'],
            ['EGMCS', 'MEO', 'Management des Entreprises et des Organisations', 'Management', 'M1,M2'],
            ['EGCGN', 'TP', null, 'GC', $tous],
            ['EGCGN', 'BAT', null, 'GC', $tous],
            ['EGCGN', 'CCI', null, 'GC', $tous],
            ['EGCGN', 'TECHNA', null, 'GN', $tous],
            ['EGCGN', 'TAN', null, 'GN', $tous],
        ];

        $ecoles = Ecole::pluck('id', 'code');

        foreach ($parcours as [$ecole, $code, $libelle, $mention, $niveaux]) {
            Parcours::updateOrCreate(['code' => $code], [
                'ecole_id' => $ecoles[$ecole],
                'libelle' => $libelle,
                'mention' => $mention,
                'niveaux' => $niveaux,
            ]);
        }
    }
}
