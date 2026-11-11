<?php

namespace App\Http\Controllers;

use App\Enums\StatutDemande;
use App\Models\Demande;
use App\Models\Etudiant;
use App\Models\Parcours;
use App\Models\TypeDemande;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DemandeController extends Controller
{
    public function index(Request $request): View
    {
        $demandes = Demande::with(['etudiant', 'typeDemande', 'agent'])
            ->filtre($request->query('q'), $request->query('statut'), $request->query('type'))
            ->latest('date_depot')->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('demandes.index', [
            'demandes' => $demandes,
            'types' => TypeDemande::orderBy('libelle')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $demande = new Demande([
            'etudiant_id' => $request->integer('etudiant') ?: null,
            'annee_universitaire' => $this->anneeUniversitaireCourante(),
        ]);

        return $this->form($demande);
    }

    public function store(Request $request): RedirectResponse
    {
        $demande = Demande::create([
            ...$this->validated($request),
            'cree_par' => $request->user()->id,
        ]);

        return redirect()->route('demandes.show', $demande)->with('status', "Demande {$demande->numero} enregistrée.");
    }

    public function show(Demande $demande): View
    {
        $demande->load([
            'typeDemande', 'etudiant.ecole', 'agent', 'creePar',
            'piecesFournies.pieceRequise', 'paiements.saisiPar', 'historique.utilisateur',
        ]);

        return view('demandes.show', [
            'demande' => $demande,
            'montantAttendu' => $demande->montantAttendu(),
            'agents' => User::where('actif', true)->orderBy('nom')->get(),
        ]);
    }

    public function edit(Demande $demande): View
    {
        return $this->form($demande);
    }

    public function update(Request $request, Demande $demande): RedirectResponse
    {
        $demande->update($this->validated($request));

        return redirect()->route('demandes.show', $demande)->with('status', 'Demande modifiée.');
    }

    public function destroy(Demande $demande): RedirectResponse
    {
        if ($demande->statut !== StatutDemande::Enregistree) {
            return back()->with('error', 'Seule une demande encore au statut « Enregistrée » peut être supprimée.');
        }

        $demande->delete();

        return redirect()->route('demandes.index')->with('status', "Demande {$demande->numero} supprimée.");
    }

    private function form(Demande $demande): View
    {
        return view('demandes.form', [
            'demande' => $demande,
            'types' => TypeDemande::orderBy('libelle')->get(),
            'etudiants' => Etudiant::orderBy('nom')->orderBy('prenoms')->get(['id', 'nom', 'prenoms']),
            'parcours' => Parcours::orderBy('code')->pluck('code'),
        ]);
    }

    /** Année universitaire en cours : elle bascule en septembre (ex. 2026-2027). */
    private function anneeUniversitaireCourante(): string
    {
        $debut = now()->month >= 9 ? now()->year : now()->year - 1;

        return $debut.'-'.($debut + 1);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'type_demande_id' => ['required', 'exists:types_demande,id'],
            'etudiant_id' => ['required', 'exists:etudiants,id'],
            'niveau' => ['nullable', Rule::in(Parcours::NIVEAUX)],
            'parcours' => ['nullable', 'string', 'max:20'],
            'annee_universitaire' => ['nullable', 'regex:/^\d{4}-\d{4}$/'],
            'mode_retrait' => ['nullable', Rule::in(array_keys(Demande::MODES_RETRAIT))],
            'observation' => ['nullable', 'string', 'max:2000'],
        ], [
            'annee_universitaire.regex' => "L'année universitaire doit être au format 2025-2026.",
        ]);
    }
}
