<?php

namespace App\Models;

use App\Enums\Diplome;
use App\Enums\VersionAttestation;
use App\Models\Concerns\Auditable;
use Database\Factories\AttestationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['numero', 'etudiant_id', 'diplome', 'date_deliberation', 'version', 'date_retrait'])]
class Attestation extends Model
{
    /** @use HasFactory<AttestationFactory> */
    use Auditable, HasFactory;

    protected function casts(): array
    {
        return [
            'diplome' => Diplome::class,
            'version' => VersionAttestation::class,
            'date_deliberation' => 'date',
            'date_retrait' => 'date',
        ];
    }

    /** Numéro proposé par défaut, au format ATT-2026-0001. */
    public static function prochainNumero(): string
    {
        $prefixe = 'ATT-'.now()->year.'-';
        $dernier = static::where('numero', 'like', $prefixe.'%')->max('numero');
        $sequence = $dernier ? (int) Str::after($dernier, $prefixe) + 1 : 1;

        return $prefixe.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function libelleAudit(): string
    {
        return "Attestation {$this->numero}";
    }
}
