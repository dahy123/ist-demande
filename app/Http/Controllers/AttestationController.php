<?php

namespace App\Http\Controllers;

use App\Enums\Diplome;
use App\Enums\VersionAttestation;
use App\Models\Attestation;
use App\Models\Etudiant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttestationController extends Controller
{
    public function index(Request $request): View
    {
        $attestations = Attestation::with('etudiant')
            ->when($request->query('q'), fn ($query, $terme) => $query->where(function ($query) use ($terme): void {
                $query->where('numero', 'like', "%{$terme}%")
                    ->orWhereHas('etudiant', fn ($query) => $query->recherche($terme));
            }))
            ->when($request->query('version'), fn ($query, $version) => $query->where('version', $version))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('attestations.index', compact('attestations'));
    }

    public function create(Request $request): View
    {
        return $this->form(new Attestation([
            'numero' => Attestation::prochainNumero(),
            'etudiant_id' => $request->integer('etudiant') ?: null,
            'version' => VersionAttestation::Provisoire,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $attestation = Attestation::create($this->validated($request));

        return redirect()->route('attestations.index')->with('status', "Attestation {$attestation->numero} enregistrée.");
    }

    public function edit(Attestation $attestation): View
    {
        return $this->form($attestation);
    }

    public function update(Request $request, Attestation $attestation): RedirectResponse
    {
        $attestation->update($this->validated($request, $attestation));

        return redirect()->route('attestations.index')->with('status', 'Attestation modifiée.');
    }

    public function destroy(Attestation $attestation): RedirectResponse
    {
        $attestation->delete();

        return redirect()->route('attestations.index')->with('status', "Attestation {$attestation->numero} supprimée.");
    }

    private function form(Attestation $attestation): View
    {
        return view('attestations.form', [
            'attestation' => $attestation,
            'etudiants' => Etudiant::orderBy('nom')->orderBy('prenoms')->get(['id', 'nom', 'prenoms']),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Attestation $attestation = null): array
    {
        return $request->validate([
            'numero' => ['required', 'string', 'max:20', Rule::unique('attestations')->ignore($attestation)],
            'etudiant_id' => ['required', 'exists:etudiants,id'],
            'diplome' => ['required', Rule::enum(Diplome::class)],
            'date_deliberation' => ['nullable', 'date'],
            'version' => ['required', Rule::enum(VersionAttestation::class)],
            'date_retrait' => ['nullable', 'date', 'after_or_equal:date_deliberation'],
        ]);
    }
}
