<?php

namespace App\Http\Controllers;

use App\Enums\Diplome;
use App\Models\Ecole;
use App\Models\Etudiant;
use App\Models\Parcours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EtudiantController extends Controller
{
    public function index(Request $request): View
    {
        $etudiants = Etudiant::with('ecole')
            ->recherche($request->query('q'))
            ->when($request->query('ecole'), fn ($query, $ecoleId) => $query->where('ecole_id', $ecoleId))
            ->orderBy('nom')->orderBy('prenoms')
            ->paginate(15)
            ->withQueryString();

        return view('etudiants.index', [
            'etudiants' => $etudiants,
            'ecoles' => Ecole::orderBy('code')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Etudiant);
    }

    public function store(Request $request): RedirectResponse
    {
        $etudiant = Etudiant::create($this->validated($request));

        return redirect()->route('etudiants.show', $etudiant)->with('status', 'Étudiant enregistré.');
    }

    public function show(Etudiant $etudiant): View
    {
        $etudiant->load([
            'ecole',
            'demandes' => fn ($query) => $query->with('typeDemande')->latest('date_depot'),
            'quitus' => fn ($query) => $query->latest('annee'),
            'attestations' => fn ($query) => $query->latest(),
        ]);

        return view('etudiants.show', compact('etudiant'));
    }

    public function edit(Etudiant $etudiant): View
    {
        return $this->form($etudiant);
    }

    public function update(Request $request, Etudiant $etudiant): RedirectResponse
    {
        $etudiant->update($this->validated($request));

        return redirect()->route('etudiants.show', $etudiant)->with('status', 'Étudiant modifié.');
    }

    public function destroy(Etudiant $etudiant): RedirectResponse
    {
        if ($etudiant->aDesDossiers()) {
            return back()->with('error', 'Impossible de supprimer un étudiant qui a des demandes, des quitus ou des attestations.');
        }

        $etudiant->delete();

        return redirect()->route('etudiants.index')->with('status', 'Étudiant supprimé.');
    }

    private function form(Etudiant $etudiant): View
    {
        return view('etudiants.form', [
            'etudiant' => $etudiant,
            'ecoles' => Ecole::orderBy('code')->get(),
            'parcours' => Parcours::orderBy('code')->get(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:80'],
            'prenoms' => ['nullable', 'string', 'max:120'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'lieu_naissance' => ['nullable', 'string', 'max:80'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'adresse' => ['nullable', 'string', 'max:160'],
            'ecole_id' => ['nullable', 'exists:ecoles,id'],
            'mention' => ['nullable', 'string', 'max:100'],
            'parcours' => ['nullable', 'string', 'max:100'],
            'promotion' => ['nullable', 'string', 'max:20'],
            'diplome_vise' => ['nullable', Rule::enum(Diplome::class)],
        ]);
    }
}
