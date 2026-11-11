<?php

namespace App\Http\Controllers;

use App\Enums\TypeJustificatif;
use App\Models\Demande;
use App\Models\Paiement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaiementController extends Controller
{
    public function store(Request $request, Demande $demande): RedirectResponse
    {
        $data = $request->validateWithBag('paiement', [
            'type_justificatif' => ['required', Rule::enum(TypeJustificatif::class)],
            'numero' => [
                'required', 'string', 'max:30',
                Rule::unique('paiements')->where('type_justificatif', $request->input('type_justificatif'))->where('annee', $request->input('annee')),
            ],
            'annee' => ['required', 'integer', 'min:2000', 'max:2100'],
            'date_paiement' => ['nullable', 'date', 'before_or_equal:today'],
            'montant_paye' => ['required', 'integer', 'min:1'],
        ], [
            'numero.unique' => 'Ce justificatif a déjà été utilisé pour un autre paiement.',
        ]);

        $demande->paiements()->create([
            ...$data,
            'montant_attendu' => $demande->montantAttendu(),
            'saisi_par' => $request->user()->id,
        ]);

        return back()->with('status', 'Paiement enregistré.');
    }

    public function destroy(Paiement $paiement): RedirectResponse
    {
        $paiement->delete();

        return back()->with('status', 'Paiement supprimé.');
    }
}
