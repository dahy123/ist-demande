<?php

namespace App\Models\Concerns;

use App\Models\JournalAudit;

/**
 * Enregistre automatiquement les créations, modifications et suppressions
 * du modèle dans le journal d'audit.
 */
trait Auditable
{
    /** Colonnes dont la seule modification ne mérite pas une ligne de journal. */
    protected static array $colonnesNonAuditees = ['updated_at', 'remember_token'];

    public static function bootAuditable(): void
    {
        static::created(fn (self $model) => JournalAudit::enregistrer('Création', $model->libelleAudit()));

        static::updated(function (self $model): void {
            if (array_diff(array_keys($model->getChanges()), static::$colonnesNonAuditees) === []) {
                return;
            }

            JournalAudit::enregistrer('Modification', $model->libelleAudit());
        });

        static::deleted(fn (self $model) => JournalAudit::enregistrer('Suppression', $model->libelleAudit()));
    }

    /** Texte décrivant l'enregistrement dans le journal (surchargeable). */
    public function libelleAudit(): string
    {
        return class_basename($this).' #'.$this->getKey();
    }
}
