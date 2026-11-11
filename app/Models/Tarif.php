<?php

namespace App\Models;

use App\Enums\Diplome;
use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['type_demande_id', 'niveau_diplome', 'montant', 'montant_retard', 'date_debut', 'date_fin'])]
class Tarif extends Model
{
    use Auditable;

    protected function casts(): array
    {
        return [
            'niveau_diplome' => Diplome::class,
            'date_debut' => 'date',
            'date_fin' => 'date',
        ];
    }

    public function typeDemande(): BelongsTo
    {
        return $this->belongsTo(TypeDemande::class);
    }
}
