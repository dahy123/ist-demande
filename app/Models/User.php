<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['nom', 'prenoms', 'identifiant', 'email', 'password', 'service', 'actif'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'utilisateur_role');
    }

    public function hasRole(string $code): bool
    {
        return $this->roles->contains('code', $code);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function libelleAudit(): string
    {
        return "Utilisateur {$this->identifiant}";
    }

    /** Nom complet : "Prénoms Nom". */
    protected function nomComplet(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->prenoms} {$this->nom}"));
    }

    /** Initiales pour l'avatar. */
    protected function initiales(): Attribute
    {
        return Attribute::get(fn () => Str::upper(
            Str::substr($this->prenoms ?: $this->nom, 0, 1).Str::substr($this->nom, 0, 1)
        ));
    }
}
