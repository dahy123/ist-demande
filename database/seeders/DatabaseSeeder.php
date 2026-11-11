<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(ReferentielSeeder::class);

        // Compte administrateur par défaut (à changer après la première connexion).
        $admin = User::updateOrCreate(
            ['identifiant' => 'admin'],
            [
                'nom' => 'Administrateur',
                'prenoms' => 'Compte',
                'password' => 'password',
                'service' => 'Scolarité',
                'actif' => true,
            ],
        );

        $admin->roles()->syncWithoutDetaching(Role::where('code', Role::ADMIN)->pluck('id'));

        $oldon = User::updateOrCreate(
            ['identifiant' => 'oldon'],
            [
                'nom' => 'Oldon',
                'password' => '1234',
                'actif' => true,
            ],
        );

        $oldon->roles()->syncWithoutDetaching(Role::where('code', Role::ADMIN)->pluck('id'));
    }
}
