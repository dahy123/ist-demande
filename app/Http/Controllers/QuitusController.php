<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Quitus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Quitus de fin d'études et leur circuit de 6 visas.
 */
class QuitusController extends Controller
{
    public function index(Request $request): View
    {
        $quitus = Quitus::with(['etudiant', 'visas'])
            ->when($request->query('q'), fn ($query, $terme) => $query->where(function ($query) use ($terme): void {
                $query->where('numero', 'like', "%{$terme}%")
                    ->orWhereHas('etudiant', fn ($query) => $query->recherche($terme));
            }))
            ->when($request->query('annee'), fn ($query, $annee) => $query->where('annee', $annee))
            ->latest('annee')->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('quitus.index', [
            'quitus' => $quitus,
            'annees' => Quitus::distinct()->orderByDesc('annee')->pluck('annee'),
        ]);
    }

    public function create(Request $request): View
    {
        return $this->form(new Quitus([
            'etudiant_id' => $request->integer('etudiant') ?: null,
            'annee' => now()->year,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $quitus = Quitus::create($this->validated($request));

        return redirect()->route('quitus.show', $quitus)->with('status', "Quitus {$quitus->numero} créé.");
    }

    public function show(Quitus $quitus): View
    {
        $quitus->load(['etudiant.ecole', 'visas']);

        return view('quitus.show', compact('quitus'));
    }

    public function edit(Quitus $quitus): View
    {
        return $this->form($quitus);
    }

    public function update(Request $request, Quitus $quitus): RedirectResponse
    {
        $quitus->update($this->validated($request, $quitus));

        return redirect()->route('quitus.show', $quitus)->with('status', 'Quitus modifié.');
    }

    public function destroy(Quitus $quitus): RedirectResponse
    {
        $quitus->delete();

        return redirect()->route('quitus.index')->with('status', "Quitus {$quitus->numero} supprimé.");
    }

    private function form(Quitus $quitus): View
    {
        return view('quitus.form', [
            'quitus' => $quitus,
            'etudiants' => Etudiant::orderBy('nom')->orderBy('prenoms')->get(['id', 'nom', 'prenoms']),
        ]);
    }

    /**
     * @return array{etudiant_id: int, annee: int, date_quitus: ?string, en_retard: bool, frais_retard: int}
     */
    private function validated(Request $request, ?Quitus $quitus = null): array
    {
        $data = $request->validate([
            'etudiant_id' => [
                'required', 'exists:etudiants,id',
                Rule::unique('quitus')->where('annee', $request->input('annee'))->ignore($quitus),
            ],
            'annee' => ['required', 'integer', 'min:2000', 'max:2100'],
            'date_quitus' => ['nullable', 'date'],
            'frais_retard' => ['nullable', 'integer', 'min:0'],
        ], [
            'etudiant_id.unique' => 'Cet étudiant a déjà un quitus pour cette année.',
        ]);

        $data['en_retard'] = $request->boolean('en_retard');
        $data['frais_retard'] = $data['en_retard'] ? (int) ($data['frais_retard'] ?? 0) : 0;

        return $data;
    }
}
