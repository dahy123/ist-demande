<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Database\Factories\EcoleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'nom'])]
class Ecole extends Model
{
    /** @use HasFactory<EcoleFactory> */
    use Auditable, HasFactory;

    public function parcours(): HasMany
    {
        return $this->hasMany(Parcours::class);
    }

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    public function libelleAudit(): string
    {
        return "École {$this->code}";
    }
}
