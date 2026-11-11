<?php

namespace App\Http\Controllers;

use App\Models\TypeDemande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Types de demande, avec leurs tarifs et leurs pièces requises gérés sur la même fiche.
 */
class TypeDemandeController extends Controller
{
    public function index(): View
    {
        $types = TypeDemande::withCount(['demandes', 'tarifs', 'piecesRequises'])->orderBy('libelle')->get();

        return view('types-demande.index', compact('types'));
    }

    public function create(): View
    {
        return view('types-demande.create', ['typeDemande' => new TypeDemande]);
    }

    public function store(Request $request): RedirectResponse
    {
        $typeDemande = TypeDemande::create($this->validated($request));

        return redirect()->route('types-demande.show', $typeDemande)
            ->with('status', 'Type de demande créé. Ajoutez maintenant ses tarifs et ses pièces requises.');
    }

    public function show(TypeDemande $typeDemande): View
    {
        $typeDemande->load([
            'tarifs' => fn ($query) => $query->latest('date_debut'),
            'piecesRequises',
        ]);

        return view('types-demande.show', compact('typeDemande'));
    }

    public function update(Request $request, TypeDemande $typeDemande): RedirectResponse
    {
        $typeDemande->update($this->validated($request, $typeDemande));

        return back()->with('status', 'Type de demande modifié.');
    }

    public function destroy(TypeDemande $typeDemande): RedirectResponse
    {
        if ($typeDemande->demandes()->exists()) {
            return back()->with('error', 'Impossible de supprimer un type déjà utilisé par des demandes.');
        }

        $typeDemande->delete();

        return redirect()->route('types-demande.index')->with('status', 'Type de demande supprimé.');
    }

    /**
     * @return array{code: string, libelle: string, delai_cible_jours: ?int}
     */
    private function validated(Request $request, ?TypeDemande $typeDemande = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:30', 'alpha_dash', Rule::unique('types_demande')->ignore($typeDemande)],
            'libelle' => ['required', 'string', 'max:80'],
            'delai_cible_jours' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);
    }
}
