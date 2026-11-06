<x-layouts.guest title="Connexion">
    <div class="grid min-h-screen lg:grid-cols-2">
        {{-- Panneau visuel --}}
        <div class="relative hidden overflow-hidden bg-brand-900 p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -top-24 -right-24 size-96 rounded-full bg-brand-600/40 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-16 size-96 rounded-full bg-brand-500/30 blur-3xl"></div>

            <div class="relative flex items-center gap-2.5">
                <span class="grid size-9 place-items-center rounded-lg bg-white text-xs font-bold text-brand-700">IST</span>
                <span class="font-semibold">IST Demandes</span>
            </div>

            <div class="relative max-w-md">
                <h2 class="text-3xl leading-tight font-semibold">Suivez chaque demande, du dépôt à la livraison.</h2>
                <p class="mt-3 text-sm text-brand-100/80">Relevés de notes, attestations, quitus, diplômes : un seul espace pour enregistrer, traiter et délivrer les documents.</p>
            </div>

            <p class="relative text-xs text-brand-100/60">&copy; {{ date('Y') }} Institut Supérieur de Technologie</p>
        </div>

        {{-- Formulaire --}}
        <div class="relative flex items-center justify-center px-4 py-10">
            <x-theme-toggle class="absolute top-4 right-4" />

            <div class="w-full max-w-sm">
                <div class="mb-6 flex items-center gap-2.5 lg:hidden">
                    <span class="grid size-8 place-items-center rounded-lg bg-brand-600 text-xs font-bold text-white">IST</span>
                    <span class="font-semibold">IST Demandes</span>
                </div>

                <h1 class="text-xl font-semibold">Connexion</h1>
                <p class="mt-1 text-sm text-muted">Saisissez vos identifiants pour accéder à votre espace.</p>

                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf

                    <x-input label="Identifiant" name="identifiant" autocomplete="username" autofocus required />
                    <x-input label="Mot de passe" name="password" type="password" autocomplete="current-password" required />

                    <label class="flex items-center gap-2 text-sm text-muted">
                        <input type="checkbox" name="remember" class="size-4 rounded accent-brand-600">
                        Se souvenir de moi
                    </label>

                    <button type="submit" class="btn-primary w-full">
                        <x-icon name="lock" class="size-4" /> Se connecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.guest>
