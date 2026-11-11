<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EcoleController extends Controller
{
    public function index(): View
    {
        $ecoles = Ecole::withCount(['parcours', 'etudiants'])->orderBy('code')->get();

        return view('ecoles.index', compact('ecoles'));
    }

    public function create(): View
    {
        return view('ecoles.form', ['ecole' => new Ecole]);
    }

    public function store(Request $request): RedirectResponse
    {
        Ecole::create($this->validated($request));

        return redirect()->route('ecoles.index')->with('status', 'École créée.');
    }

    public function edit(Ecole $ecole): View
    {
        return view('ecoles.form', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole): RedirectResponse
    {
        $ecole->update($this->validated($request, $ecole));

        return redirect()->route('ecoles.index')->with('status', 'École modifiée.');
    }

    public function destroy(Ecole $ecole): RedirectResponse
    {
        if ($ecole->parcours()->exists() || $ecole->etudiants()->exists()) {
            return back()->with('error', 'Impossible de supprimer une école qui a des parcours ou des étudiants.');
        }

        $ecole->delete();

        return redirect()->route('ecoles.index')->with('status', 'École supprimée.');
    }

    /**
     * @return array{code: string, nom: string}
     */
    private function validated(Request $request, ?Ecole $ecole = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('ecoles')->ignore($ecole)],
            'nom' => ['required', 'string', 'max:120'],
        ]);
    }
}
