<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use App\Models\Parcours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ParcoursController extends Controller
{
    public function index(Request $request): View
    {
        $parcours = Parcours::with('ecole')
            ->when($request->query('ecole'), fn ($query, $ecoleId) => $query->where('ecole_id', $ecoleId))
            ->orderBy('code')
            ->get();

        return view('parcours.index', [
            'parcours' => $parcours,
            'ecoles' => Ecole::orderBy('code')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Parcours);
    }

    public function store(Request $request): RedirectResponse
    {
        Parcours::create($this->validated($request));

        return redirect()->route('parcours.index')->with('status', 'Parcours créé.');
    }

    public function edit(Parcours $parcours): View
    {
        return $this->form($parcours);
    }

    public function update(Request $request, Parcours $parcours): RedirectResponse
    {
        $parcours->update($this->validated($request, $parcours));

        return redirect()->route('parcours.index')->with('status', 'Parcours modifié.');
    }

    public function destroy(Parcours $parcours): RedirectResponse
    {
        $parcours->delete();

        return redirect()->route('parcours.index')->with('status', 'Parcours supprimé.');
    }

    private function form(Parcours $parcours): View
    {
        return view('parcours.form', [
            'parcours' => $parcours,
            'ecoles' => Ecole::orderBy('code')->get(),
        ]);
    }

    /**
     * @return array{code: string, libelle: ?string, ecole_id: int, mention: ?string, niveaux: string}
     */
    private function validated(Request $request, ?Parcours $parcours = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('parcours')->ignore($parcours)],
            'libelle' => ['nullable', 'string', 'max:120'],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'mention' => ['nullable', 'string', 'max:40'],
            'niveaux' => ['required', 'array', 'min:1'],
            'niveaux.*' => [Rule::in(Parcours::NIVEAUX)],
        ]);

        $data['niveaux'] = implode(',', array_values(array_intersect(Parcours::NIVEAUX, $data['niveaux'])));

        return $data;
    }
}
