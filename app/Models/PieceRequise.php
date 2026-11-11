<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'pieces_requises')]
#[Fillable(['type_demande_id', 'libelle', 'obligatoire'])]
class PieceRequise extends Model
{
    protected function casts(): array
    {
        return ['obligatoire' => 'boolean'];
    }

    public function typeDemande(): BelongsTo
    {
        return $this->belongsTo(TypeDemande::class);
    }
}
