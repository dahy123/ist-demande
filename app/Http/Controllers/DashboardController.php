<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // Statistiques provisoires : à brancher sur le modèle Demande quand il existera.
        $stats = [
            ['label' => 'Demandes reçues', 'value' => 0, 'icon' => 'inbox', 'tone' => 'indigo'],
            ['label' => 'En traitement', 'value' => 0, 'icon' => 'clock', 'tone' => 'amber'],
            ['label' => 'Traitées', 'value' => 0, 'icon' => 'check', 'tone' => 'emerald'],
            ['label' => 'Livrées', 'value' => 0, 'icon' => 'truck', 'tone' => 'sky'],
        ];

        return view('dashboard', compact('stats'));
    }
}
