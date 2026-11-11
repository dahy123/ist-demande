<?php

namespace App\Models;

use App\Enums\StatutDemande;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'historique_statut', timestamps: false)]
#[Fillable(['demande_id', 'ancien_statut', 'nouveau_statut', 'utilisateur_id', 'commentaire', 'date_heure'])]
class HistoriqueStatut extends Model
{
    protected function casts(): array
    {
        return [
            'ancien_statut' => StatutDemande::class,
            'nouveau_statut' => StatutDemande::class,
            'date_heure' => 'datetime',
        ];
    }

    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
