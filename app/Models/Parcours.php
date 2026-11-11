<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'parcours')]
#[Fillable(['code', 'libelle', 'ecole_id', 'mention', 'niveaux'])]
class Parcours extends Model
{
    use Auditable;

    /** Niveaux d'études proposés par l'établissement. */
    public const NIVEAUX = ['L1', 'L2', 'L3', 'M1', 'M2'];

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function libelleAudit(): string
    {
        return "Parcours {$this->code}";
    }
}
