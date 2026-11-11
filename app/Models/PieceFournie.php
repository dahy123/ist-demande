<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'pieces_fournies')]
#[Fillable(['demande_id', 'piece_requise_id', 'fournie', 'date_fourniture'])]
class PieceFournie extends Model
{
    protected function casts(): array
    {
        return [
            'fournie' => 'boolean',
            'date_fourniture' => 'date',
        ];
    }

    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function pieceRequise(): BelongsTo
    {
        return $this->belongsTo(PieceRequise::class);
    }
}
