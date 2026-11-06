<x-layouts.app title="Mon profil">
    <div class="grid gap-3 xl:grid-cols-3">
        {{-- Carte identité --}}
        <div class="card h-fit text-center">
            <span class="mx-auto grid size-16 place-items-center rounded-xl bg-brand-100 text-xl font-semibold text-brand-700 dark:bg-brand-500/20 dark:text-brand-300">{{ $user->initiales }}</span>
            <h2 class="mt-3 font-semibold">{{ $user->nom_complet }}</h2>
            <p class="text-sm text-muted">{{ $user->service ?? '—' }}</p>

            <dl class="mt-4 space-y-2 border-t border-line pt-4 text-left text-sm">
                <div class="flex justify-between gap-4"><dt class="text-muted">Identifiant</dt><dd class="font-medium">{{ $user->identifiant }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">E-mail</dt><dd class="truncate font-medium">{{ $user->email ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Membre depuis</dt><dd class="font-medium">{{ $user->created_at?->format('d/m/Y') }}</dd></div>
            </dl>
        </div>

        <div class="space-y-3 xl:col-span-2">
            {{-- Informations personnelles --}}
            <form method="POST" action="{{ route('profile.update') }}" class="card">
                @csrf
                @method('PUT')

                <h2 class="text-sm font-semibold">Informations personnelles</h2>
                <p class="text-xs text-muted">Mettez à jour vos informations de compte.</p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <x-input label="Nom" name="nom" :value="$user->nom" required />
                    <x-input label="Prénoms" name="prenoms" :value="$user->prenoms" />
                    <x-input label="E-mail" name="email" type="email" :value="$user->email" />
                    <x-input label="Service" name="service" :value="$user->service" />
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>

            {{-- Mot de passe --}}
            <form method="POST" action="{{ route('profile.password') }}" class="card">
                @csrf
                @method('PUT')

                <h2 class="text-sm font-semibold">Mot de passe</h2>
                <p class="text-xs text-muted">Utilisez au moins 8 caractères.</p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-input label="Mot de passe actuel" name="current_password" type="password" bag="password" autocomplete="current-password" />
                    </div>
                    <x-input label="Nouveau mot de passe" name="password" type="password" bag="password" autocomplete="new-password" />
                    <x-input label="Confirmation" name="password_confirmation" type="password" bag="password" autocomplete="new-password" />
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn-primary">Modifier le mot de passe</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
