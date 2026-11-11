<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenoms' => fake()->firstName(),
            'identifiant' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'service' => 'Scolarité',
            'actif' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function inactif(): static
    {
        return $this->state(fn () => ['actif' => false]);
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user): void {
            $user->roles()->attach(Role::firstOrCreate(['code' => Role::ADMIN], ['libelle' => 'Administrateur']));
        });
    }
}
