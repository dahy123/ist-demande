<?php

namespace App\Models;

use App\Enums\StatutDemande;
use App\Models\Concerns\Auditable;
use Database\Factories\DemandeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'type_demande_id', 'etudiant_id', 'agent_id', 'niveau', 'parcours', 'annee_universitaire',
    'statut', 'date_depot', 'date_livraison', 'mode_retrait', 'observation', 'cree_par',
])]
class Demande extends Model
{
    /** @use HasFactory<DemandeFactory> */
    use Auditable, HasFactory;

    /** Modes de retrait du document. */
    public const MODES_RETRAIT = ['ETUDIANT' => 'Par l\'étudiant', 'MANDATAIRE' => 'Par un mandataire'];

    protected $attributes = [
        'statut' => 'ENREGISTREE',
    ];

    protected function casts(): array
    {
        return [
            'statut' => StatutDemande::class,
            'date_depot' => 'datetime',
            'date_livraison' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Demande $demande): void {
            $demande->numero ??= static::prochainNumero();
            $demande->date_depot ??= now();
        });

        // Une nouvelle demande reçoit la liste des pièces à fournir de son type
        // et une première ligne d'historique.
        static::created(function (Demande $demande): void {
            $demande->piecesFournies()->createMany(
                $demande->typeDemande->piecesRequises()->pluck('id')
                    ->map(fn (int $pieceId) => ['piece_requise_id' => $pieceId])
                    ->all()
            );

            $demande->historique()->create([
                'nouveau_statut' => $demande->statut,
                'utilisateur_id' => auth()->id(),
                'date_heure' => now(),
            ]);
        });
    }

    /** Numéro au format DEM-2026-00001, remis à zéro chaque année. */
    public static function prochainNumero(): string
    {
        $prefixe = 'DEM-'.now()->year.'-';
        $dernier = static::where('numero', 'like', $prefixe.'%')->max('numero');
        $sequence = $dernier ? (int) Str::after($dernier, $prefixe) + 1 : 1;

        return $prefixe.str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }

    public function typeDemande(): BelongsTo
    {
        return $this->belongsTo(TypeDemande::class);
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function creePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function piecesFournies(): HasMany
    {
        return $this->hasMany(PieceFournie::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function historique(): HasMany
    {
        return $this->hasMany(HistoriqueStatut::class)->latest('date_heure')->latest('id');
    }

    /** Passe la demande à un nouveau statut et trace le changement. */
    public function changerStatut(StatutDemande $nouveauStatut, ?string $commentaire = null): void
    {
        $ancienStatut = $this->statut;

        if ($ancienStatut === $nouveauStatut) {
            return;
        }

        $this->statut = $nouveauStatut;

        if ($nouveauStatut === StatutDemande::Livree) {
            $this->date_livraison ??= now();
        }

        $this->save();

        $this->historique()->create([
            'ancien_statut' => $ancienStatut,
            'nouveau_statut' => $nouveauStatut,
            'utilisateur_id' => auth()->id(),
            'commentaire' => $commentaire,
            'date_heure' => now(),
        ]);
    }

    /** Montant dû selon le tarif en vigueur pour le diplôme visé par l'étudiant. */
    public function montantAttendu(): ?int
    {
        return $this->typeDemande->tarifApplicable($this->etudiant->diplome_vise, $this->date_depot)?->montant;
    }

    /** Total des paiements enregistrés pour la demande. */
    public function montantPaye(): int
    {
        return (int) $this->paiements->sum('montant_paye');
    }

    /** Vrai quand les paiements couvrent le tarif applicable (ou qu'aucun tarif n'est prévu). */
    public function estPayee(): bool
    {
        return $this->montantPaye() >= ($this->montantAttendu() ?? 0);
    }

    /**
     * Pièces obligatoires du dossier qui n'ont pas encore été fournies.
     *
     * @return Collection<int, PieceFournie>
     */
    public function piecesObligatoiresManquantes(): Collection
    {
        return $this->piecesFournies
            ->loadMissing('pieceRequise')
            ->filter(fn (PieceFournie $piece) => $piece->pieceRequise->obligatoire && ! $piece->fournie)
            ->values();
    }

    public function scopeFiltre(Builder $query, ?string $terme, ?string $statut, ?string $typeDemandeId): void
    {
        $query
            ->when($terme, fn (Builder $query) => $query->where(function (Builder $query) use ($terme): void {
                $query->where('numero', 'like', "%{$terme}%")
                    ->orWhereHas('etudiant', fn (Builder $query) => $query->recherche($terme));
            }))
            ->when($statut, fn (Builder $query) => $query->where('statut', $statut))
            ->when($typeDemandeId, fn (Builder $query) => $query->where('type_demande_id', $typeDemandeId));
    }

    public function libelleAudit(): string
    {
        return "Demande {$this->numero}";
    }
}
