<?php

namespace App\Http\Controllers;

use App\Enums\StatutDemande;
use App\Models\Demande;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $parStatut = Demande::toBase()->selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $compter = fn (array $statuts): int => collect($statuts)->sum(fn (StatutDemande $statut) => $parStatut[$statut->value] ?? 0);

        $stats = [
            ['label' => 'Demandes reçues', 'value' => $parStatut->sum(), 'icon' => 'inbox', 'tone' => 'indigo'],
            ['label' => 'En traitement', 'value' => $compter(StatutDemande::enCours()), 'icon' => 'clock', 'tone' => 'amber'],
            ['label' => 'Traitées', 'value' => $compter([StatutDemande::Traitee]), 'icon' => 'check', 'tone' => 'emerald'],
            ['label' => 'Livrées', 'value' => $compter([StatutDemande::Livree]), 'icon' => 'truck', 'tone' => 'sky'],
        ];

        // Nombre de demandes déposées chaque mois de l'année en cours (regroupement en PHP pour rester indépendant du SGBD).
        $parMois = Demande::whereYear('date_depot', now()->year)->pluck('date_depot')
            ->countBy(fn ($date) => $date->month);
        $mois = collect(range(1, 12))->mapWithKeys(fn (int $mois) => [$mois => $parMois[$mois] ?? 0]);

        return view('dashboard', [
            'stats' => $stats,
            'mois' => $mois,
            'dernieresDemandes' => Demande::with(['etudiant', 'typeDemande'])->latest('date_depot')->latest('id')->limit(6)->get(),
        ]);
    }
}
