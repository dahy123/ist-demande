<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Database\Factories\QuitusFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'quitus')]
#[Fillable(['numero', 'annee', 'etudiant_id', 'date_quitus', 'en_retard', 'frais_retard'])]
class Quitus extends Model
{
    /** @use HasFactory<QuitusFactory> */
    use Auditable, HasFactory;

    /**
     * Les 6 services qui doivent viser le quitus, dans l'ordre.
     *
     * @var array<int, string>
     */
    public const SERVICES_VISA = [
        1 => 'Bibliothèque',
        2 => 'Laboratoire / Atelier',
        3 => 'Service des stages',
        4 => 'Comptabilité',
        5 => 'Scolarité',
        6 => "Direction de l'école",
    ];

    protected function casts(): array
    {
        return [
            'date_quitus' => 'date',
            'en_retard' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Quitus $quitus): void {
            $quitus->numero ??= static::prochainNumero($quitus->annee);
        });

        static::created(function (Quitus $quitus): void {
            $quitus->visas()->createMany(
                collect(self::SERVICES_VISA)
                    ->map(fn (string $service, int $position) => ['position' => $position, 'service' => $service])
                    ->values()
                    ->all()
            );
        });
    }

    /** Numéro au format 0001/2026. */
    public static function prochainNumero(int $annee): string
    {
        $sequence = static::where('annee', $annee)->count() + 1;

        return str_pad((string) $sequence, 4, '0', STR_PAD_LEFT).'/'.$annee;
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function visas(): HasMany
    {
        return $this->hasMany(QuitusVisa::class)->orderBy('position');
    }

    /** Un quitus est complet quand ses 6 visas sont validés. */
    public function estComplet(): bool
    {
        return $this->visas->isNotEmpty() && $this->visas->every('valide');
    }

    public function libelleAudit(): string
    {
        return "Quitus {$this->numero}";
    }
}
