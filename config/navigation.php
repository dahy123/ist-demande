<?php

/*
|--------------------------------------------------------------------------
| Menu de la sidebar
|--------------------------------------------------------------------------
| Chaque section contient des liens : label, route, icon (voir
| resources/views/components/icon.blade.php) et active (motif de nom de route).
| Une section avec 'can' n'est affichée qu'aux utilisateurs ayant cette autorisation.
| Pour ajouter un module, il suffit d'ajouter une entrée ici.
*/

return [
    [
        'title' => 'Général',
        'items' => [
            ['label' => 'Tableau de bord', 'route' => 'dashboard', 'icon' => 'home', 'active' => 'dashboard'],
            ['label' => 'Demandes', 'route' => 'demandes.index', 'icon' => 'inbox', 'active' => 'demandes.*'],
            ['label' => 'Étudiants', 'route' => 'etudiants.index', 'icon' => 'users', 'active' => 'etudiants.*'],
            ['label' => 'Quitus', 'route' => 'quitus.index', 'icon' => 'check', 'active' => 'quitus.*'],
            ['label' => 'Attestations', 'route' => 'attestations.index', 'icon' => 'file', 'active' => 'attestations.*'],
        ],
    ],
    [
        'title' => 'Administration',
        'can' => 'administrer',
        'items' => [
            ['label' => 'Types de demande', 'route' => 'types-demande.index', 'icon' => 'list', 'active' => 'types-demande.*'],
            ['label' => 'Écoles', 'route' => 'ecoles.index', 'icon' => 'building', 'active' => 'ecoles.*'],
            ['label' => 'Parcours', 'route' => 'parcours.index', 'icon' => 'book', 'active' => 'parcours.*'],
            ['label' => 'Utilisateurs', 'route' => 'utilisateurs.index', 'icon' => 'shield', 'active' => 'utilisateurs.*'],
            ['label' => "Journal d'audit", 'route' => 'journal.index', 'icon' => 'clock', 'active' => 'journal.*'],
        ],
    ],
    [
        'title' => 'Paramètres',
        'items' => [
            ['label' => 'Mon profil', 'route' => 'profile.edit', 'icon' => 'user', 'active' => 'profile.*'],
        ],
    ],
];
