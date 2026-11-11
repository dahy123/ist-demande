<?php

namespace App\Models;

use App\Enums\Diplome;
use App\Models\Concerns\Auditable;
use Database\Factories\EtudiantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nom', 'prenoms', 'date_naissance', 'lieu_naissance', 'telephone', 'email', 'adresse',
    'ecole_id', 'mention', 'parcours', 'promotion', 'diplome_vise',
])]
class Etudiant extends Model
{
    /** @use HasFactory<EtudiantFactory> */
    use Auditable, HasFactory;

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'diplome_vise' => Diplome::class,
        ];
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function demandes(): HasMany
    {
        return $this->hasMany(Demande::class);
    }

    public function quitus(): HasMany
    {
        return $this->hasMany(Quitus::class);
    }

    public function attestations(): HasMany
    {
        return $this->hasMany(Attestation::class);
    }

    /** Nom complet : "NOM Prénoms". */
    protected function nomComplet(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->nom} {$this->prenoms}"));
    }

    /** Recherche sur le nom, les prénoms ou l'e-mail. */
    public function scopeRecherche(Builder $query, ?string $terme): void
    {
        $query->when($terme, fn (Builder $query) => $query->where(function (Builder $query) use ($terme): void {
            $query->where('nom', 'like', "%{$terme}%")
                ->orWhere('prenoms', 'like', "%{$terme}%")
                ->orWhere('email', 'like', "%{$terme}%");
        }));
    }

    public function aDesDossiers(): bool
    {
        return $this->demandes()->exists() || $this->quitus()->exists() || $this->attestations()->exists();
    }

    public function libelleAudit(): string
    {
        return "Étudiant {$this->nom_complet}";
    }
}
