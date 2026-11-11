<x-layouts.app :title="$demande->exists ? 'Modifier la demande '.$demande->numero : 'Nouvelle demande'">
    <form method="POST" action="{{ $demande->exists ? route('demandes.update', $demande) : route('demandes.store') }}" class="card max-w-4xl space-y-5">
        @csrf
        @if ($demande->exists)
            @method('PUT')
        @endif

        <section>
            <h2 class="text-sm font-semibold">Demande</h2>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                <x-select label="Étudiant" name="etudiant_id" :options="$etudiants->pluck('nom_complet', 'id')" :value="$demande->etudiant_id" placeholder="Choisir…" required />
                <x-select label="Type de demande" name="type_demande_id" :options="$types->pluck('libelle', 'id')" :value="$demande->type_demande_id" placeholder="Choisir…" required />
            </div>
            <p class="mt-1.5 text-xs text-muted">Étudiant absent de la liste ? <a href="{{ route('etudiants.create') }}" class="text-brand-600 hover:underline">L'enregistrer d'abord</a>.</p>
        </section>

        <section class="border-t border-line pt-4">
            <h2 class="text-sm font-semibold">Inscription concernée</h2>
            <div class="mt-3 grid gap-3 sm:grid-cols-3">
                <x-select label="Niveau" name="niveau" :options="array_combine(\App\Models\Parcours::NIVEAUX, \App\Models\Parcours::NIVEAUX)" :value="$demande->niveau" placeholder="—" />
                <x-select label="Parcours" name="parcours" :options="$parcours->combine($parcours)" :value="$demande->parcours" placeholder="—" />
                <x-input label="Année universitaire" name="annee_universitaire" :value="$demande->annee_universitaire" placeholder="2025-2026" maxlength="9" />
            </div>
        </section>

        <section class="border-t border-line pt-4">
            <h2 class="text-sm font-semibold">Retrait et remarques</h2>
            <div class="mt-3 grid gap-3 sm:grid-cols-3">
                <x-select label="Mode de retrait" name="mode_retrait" :options="\App\Models\Demande::MODES_RETRAIT" :value="$demande->mode_retrait" placeholder="—" />
                <div class="sm:col-span-2">
                    <label for="observation" class="form-label">Observation</label>
                    <textarea id="observation" name="observation" rows="3" maxlength="2000" class="form-input">{{ old('observation', $demande->observation) }}</textarea>
                    @error('observation')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        @unless ($demande->exists)
            <p class="text-xs text-muted">Le numéro est attribué automatiquement et la liste des pièces à fournir est créée selon le type de demande.</p>
        @endunless

        <div class="flex justify-end gap-2">
            <a href="{{ $demande->exists ? route('demandes.show', $demande) : route('demandes.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>
