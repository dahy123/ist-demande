<?php

namespace App\Http\Controllers;

use App\Enums\Diplome;
use App\Models\Tarif;
use App\Models\TypeDemande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TarifController extends Controller
{
    public function store(Request $request, TypeDemande $typeDemande): RedirectResponse
    {
        $data = $request->validateWithBag('tarif', [
            'niveau_diplome' => ['nullable', Rule::enum(Diplome::class)],
            'montant' => ['required', 'integer', 'min:0'],
            'montant_retard' => ['nullable', 'integer', 'min:0'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
        ]);

        $typeDemande->tarifs()->create($data);

        return back()->with('status', 'Tarif ajouté.');
    }

    public function destroy(Tarif $tarif): RedirectResponse
    {
        $tarif->delete();

        return back()->with('status', 'Tarif supprimé.');
    }
}
