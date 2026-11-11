<?php

namespace App\Models;

use App\Enums\TypeJustificatif;
use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'demande_id', 'type_justificatif', 'numero', 'annee', 'date_paiement',
    'montant_paye', 'montant_attendu', 'saisi_par',
])]
class Paiement extends Model
{
    use Auditable;

    protected function casts(): array
    {
        return [
            'type_justificatif' => TypeJustificatif::class,
            'date_paiement' => 'date',
        ];
    }

    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function saisiPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'saisi_par');
    }

    public function libelleAudit(): string
    {
        return "Paiement {$this->numero} ({$this->montant_paye} Ar)";
    }
}
