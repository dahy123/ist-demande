<?php

namespace App\Http\Controllers;

use App\Models\PieceFournie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Coche ou décoche une pièce du dossier d'une demande.
 */
class PieceFournieController extends Controller
{
    public function __invoke(Request $request, PieceFournie $pieceFournie): RedirectResponse
    {
        $fournie = $request->boolean('fournie');

        $pieceFournie->update([
            'fournie' => $fournie,
            'date_fourniture' => $fournie ? ($pieceFournie->date_fourniture ?? today()) : null,
        ]);

        return back()->with('status', $fournie ? 'Pièce marquée comme fournie.' : 'Pièce marquée comme manquante.');
    }
}
