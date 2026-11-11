<?php

namespace App\Http\Controllers;

use App\Models\PieceRequise;
use App\Models\TypeDemande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PieceRequiseController extends Controller
{
    public function store(Request $request, TypeDemande $typeDemande): RedirectResponse
    {
        $data = $request->validateWithBag('piece', [
            'libelle' => ['required', 'string', 'max:160'],
            'obligatoire' => ['boolean'],
        ]);

        $typeDemande->piecesRequises()->create([
            'libelle' => $data['libelle'],
            'obligatoire' => $request->boolean('obligatoire'),
        ]);

        return back()->with('status', 'Pièce requise ajoutée.');
    }

    public function destroy(PieceRequise $pieceRequise): RedirectResponse
    {
        $pieceRequise->delete();

        return back()->with('status', 'Pièce requise supprimée.');
    }
}
