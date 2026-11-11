<x-layouts.app :title="$utilisateur->exists ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur'">
    @php($rolesCoches = array_map('intval', old('roles', $utilisateur->roles?->pluck('id')->all() ?? [])))

    <form method="POST" action="{{ $utilisateur->exists ? route('utilisateurs.update', $utilisateur) : route('utilisateurs.store') }}" class="grid gap-3 xl:grid-cols-3">
        @csrf
        @if ($utilisateur->exists)
            @method('PUT')
        @endif

        <div class="card space-y-4 xl:col-span-2">
            <div>
                <h2 class="text-sm font-semibold">Compte</h2>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <x-input label="Nom" name="nom" :value="$utilisateur->nom" maxlength="80" required />
                    <x-input label="Prénoms" name="prenoms" :value="$utilisateur->prenoms" maxlength="120" />
                    <x-input label="Identifiant de connexion" name="identifiant" :value="$utilisateur->identifiant" maxlength="40" autocomplete="off" required />
                    <x-input label="E-mail" name="email" type="email" :value="$utilisateur->email" maxlength="120" />
                    <x-input label="Service" name="service" :value="$utilisateur->service" maxlength="80" />
                </div>
            </div>

            <div class="border-t border-line pt-4">
                <h2 class="text-sm font-semibold">Mot de passe</h2>
                <p class="text-xs text-muted">{{ $utilisateur->exists ? 'Laisser vide pour conserver le mot de passe actuel.' : 'Au moins 8 caractères.' }}</p>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <x-input label="Mot de passe" name="password" type="password" autocomplete="new-password" :required="! $utilisateur->exists" />
                    <x-input label="Confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="card">
                <h2 class="text-sm font-semibold">Rôles</h2>
                <div class="mt-3 space-y-2">
                    @foreach ($roles as $role)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id, $rolesCoches)) class="size-4 rounded accent-brand-600">
                            {{ $role->libelle }}
                        </label>
                    @endforeach
                </div>
                @error('roles.*')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="card">
                <input type="hidden" name="actif" value="0">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="actif" value="1" @checked(old('actif', $utilisateur->actif)) class="size-4 rounded accent-brand-600">
                    Compte actif
                </label>
                <p class="mt-1 text-xs text-muted">Un compte désactivé ne peut plus se connecter.</p>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('utilisateurs.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </div>
    </form>
</x-layouts.app>
