<?php

namespace App\Enums;

/**
 * Étapes du circuit de traitement d'une demande.
 */
enum StatutDemande: string
{
    case Enregistree = 'ENREGISTREE';
    case PaiementVerifie = 'PAIEMENT_VERIFIE';
    case Recevable = 'RECEVABLE';
    case Affectee = 'AFFECTEE';
    case EnTraitement = 'EN_TRAITEMENT';
    case EnAttenteEtudiant = 'EN_ATTENTE_ETUDIANT';
    case EnSignature = 'EN_SIGNATURE';
    case Traitee = 'TRAITEE';
    case Livree = 'LIVREE';
    case Refusee = 'REFUSEE';

    public function label(): string
    {
        return match ($this) {
            self::Enregistree => 'Enregistrée',
            self::PaiementVerifie => 'Paiement vérifié',
            self::Recevable => 'Recevable',
            self::Affectee => 'Affectée',
            self::EnTraitement => 'En traitement',
            self::EnAttenteEtudiant => "En attente de l'étudiant",
            self::EnSignature => 'En signature',
            self::Traitee => 'Traitée',
            self::Livree => 'Livrée',
            self::Refusee => 'Refusée',
        };
    }

    /** Classes Tailwind du badge de statut. */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Enregistree, self::PaiementVerifie, self::Recevable => 'bg-brand-500/10 text-brand-600 dark:text-brand-300',
            self::Affectee, self::EnTraitement, self::EnSignature => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
            self::EnAttenteEtudiant => 'bg-orange-500/10 text-orange-600 dark:text-orange-400',
            self::Traitee => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
            self::Livree => 'bg-sky-500/10 text-sky-600 dark:text-sky-400',
            self::Refusee => 'bg-red-500/10 text-red-600 dark:text-red-400',
        };
    }

    /**
     * Statuts considérés comme « en cours de traitement » sur le tableau de bord.
     *
     * @return list<self>
     */
    public static function enCours(): array
    {
        return [self::Affectee, self::EnTraitement, self::EnAttenteEtudiant, self::EnSignature];
    }
}
