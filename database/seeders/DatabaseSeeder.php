<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Compte administrateur par défaut (à changer après la première connexion).
        User::updateOrCreate(
            ['identifiant' => 'admin'],
            [
                'nom' => 'Administrateur',
                'prenoms' => 'Compte',
                'password' => 'password',
                'service' => 'Scolarité',
                'actif' => true,
            ],
        );
    }
}
