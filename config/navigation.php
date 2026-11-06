<?php

/*
|--------------------------------------------------------------------------
| Menu de la sidebar
|--------------------------------------------------------------------------
| Chaque section contient des liens : label, route, icon (voir
| resources/views/components/icon.blade.php) et active (motif de nom de route).
| Pour ajouter un module, il suffit d'ajouter une entrée ici.
*/

return [
    [
        'title' => 'Général',
        'items' => [
            ['label' => 'Tableau de bord', 'route' => 'dashboard', 'icon' => 'home', 'active' => 'dashboard'],
            // ['label' => 'Demandes', 'route' => 'demandes.index', 'icon' => 'inbox', 'active' => 'demandes.*'],
            // ['label' => 'Étudiants', 'route' => 'etudiants.index', 'icon' => 'users', 'active' => 'etudiants.*'],
        ],
    ],
    [
        'title' => 'Paramètres',
        'items' => [
            ['label' => 'Mon profil', 'route' => 'profile.edit', 'icon' => 'user', 'active' => 'profile.*'],
        ],
    ],
];
