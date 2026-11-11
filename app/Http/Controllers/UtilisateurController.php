<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Comptes utilisateurs et leurs rôles (table utilisateur_role).
 */
class UtilisateurController extends Controller
{
    public function index(): View
    {
        $utilisateurs = User::with('roles')->orderBy('nom')->get();

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    public function create(): View
    {
        return view('utilisateurs.form', ['utilisateur' => new User(['actif' => true]), 'roles' => Role::orderBy('libelle')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data): void {
            $utilisateur = User::create($data);
            $utilisateur->roles()->sync($data['roles'] ?? []);
        });

        return redirect()->route('utilisateurs.index')->with('status', 'Utilisateur créé.');
    }

    public function edit(User $utilisateur): View
    {
        return view('utilisateurs.form', ['utilisateur' => $utilisateur->load('roles'), 'roles' => Role::orderBy('libelle')->get()]);
    }

    public function update(Request $request, User $utilisateur): RedirectResponse
    {
        $data = $this->validated($request, $utilisateur);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        if ($utilisateur->is($request->user()) && ! $data['actif']) {
            return back()->withInput()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        DB::transaction(function () use ($utilisateur, $data): void {
            $utilisateur->update($data);
            $utilisateur->roles()->sync($data['roles'] ?? []);
        });

        return redirect()->route('utilisateurs.index')->with('status', 'Utilisateur modifié.');
    }

    public function destroy(Request $request, User $utilisateur): RedirectResponse
    {
        if ($utilisateur->is($request->user())) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $utilisateur->delete();

        return redirect()->route('utilisateurs.index')->with('status', 'Utilisateur supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?User $utilisateur = null): array
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:80'],
            'prenoms' => ['nullable', 'string', 'max:120'],
            'identifiant' => ['required', 'string', 'max:40', 'alpha_dash', Rule::unique('users')->ignore($utilisateur)],
            'email' => ['nullable', 'email', 'max:120', Rule::unique('users')->ignore($utilisateur)],
            'service' => ['nullable', 'string', 'max:80'],
            'password' => [$utilisateur ? 'nullable' : 'required', 'confirmed', Password::min(8)],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $data['actif'] = $request->boolean('actif');

        return $data;
    }
}
