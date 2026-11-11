<?php

namespace App\Models;

use App\Enums\Diplome;
use App\Models\Concerns\Auditable;
use Database\Factories\TypeDemandeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Table(name: 'types_demande')]
#[Fillable(['code', 'libelle', 'delai_cible_jours'])]
class TypeDemande extends Model
{
    /** @use HasFactory<TypeDemandeFactory> */
    use Auditable, HasFactory;

    public function tarifs(): HasMany
    {
        return $this->hasMany(Tarif::class);
    }

    public function piecesRequises(): HasMany
    {
        return $this->hasMany(PieceRequise::class);
    }

    public function demandes(): HasMany
    {
        return $this->hasMany(Demande::class);
    }

    /**
     * Tarif en vigueur à une date donnée : le tarif propre au diplôme
     * est prioritaire sur le tarif général (sans diplôme).
     */
    public function tarifApplicable(?Diplome $diplome = null, ?Carbon $date = null): ?Tarif
    {
        $date ??= today();

        return $this->tarifs()
            ->whereDate('date_debut', '<=', $date)
            ->where(fn (Builder $query) => $query->whereNull('date_fin')->orWhereDate('date_fin', '>=', $date))
            ->where(fn (Builder $query) => $query->whereNull('niveau_diplome')->orWhere('niveau_diplome', $diplome?->value))
            ->orderByRaw('niveau_diplome is null')
            ->latest('date_debut')
            ->first();
    }

    public function libelleAudit(): string
    {
        return "Type de demande {$this->code}";
    }
}
