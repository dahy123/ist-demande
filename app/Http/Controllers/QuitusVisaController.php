<?php

namespace App\Http\Controllers;

use App\Models\QuitusVisa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Enregistre le visa d'un service sur un quitus.
 */
class QuitusVisaController extends Controller
{
    public function __invoke(Request $request, QuitusVisa $quitusVisa): RedirectResponse
    {
        $data = $request->validateWithBag("visa{$quitusVisa->id}", [
            'responsable' => ['nullable', 'string', 'max:100'],
            'observation' => ['nullable', 'string', 'max:160'],
        ]);

        $valide = $request->boolean('valide');

        $quitusVisa->update([
            ...$data,
            'valide' => $valide,
            'date_visa' => $valide ? ($quitusVisa->date_visa ?? today()) : null,
        ]);

        return back()->with('status', "Visa « {$quitusVisa->service} » enregistré.");
    }
}
