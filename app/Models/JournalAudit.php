<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'journal_audit', timestamps: false)]
#[Fillable(['utilisateur_id', 'action', 'detail', 'date_heure'])]
class JournalAudit extends Model
{
    protected function casts(): array
    {
        return ['date_heure' => 'datetime'];
    }

    public static function enregistrer(string $action, ?string $detail = null): self
    {
        return static::create([
            'utilisateur_id' => auth()->id(),
            'action' => $action,
            'detail' => $detail,
            'date_heure' => now(),
        ]);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
