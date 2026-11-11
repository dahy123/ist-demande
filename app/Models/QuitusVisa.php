<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'quitus_visas')]
#[Fillable(['quitus_id', 'position', 'service', 'responsable', 'observation', 'date_visa', 'valide'])]
class QuitusVisa extends Model
{
    protected function casts(): array
    {
        return [
            'date_visa' => 'date',
            'valide' => 'boolean',
        ];
    }

    public function quitus(): BelongsTo
    {
        return $this->belongsTo(Quitus::class);
    }
}
