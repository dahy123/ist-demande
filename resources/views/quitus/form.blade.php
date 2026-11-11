<x-layouts.app :title="$quitus->exists ? 'Modifier le quitus '.$quitus->numero : 'Nouveau quitus'">
    <form method="POST" action="{{ $quitus->exists ? route('quitus.update', $quitus) : route('quitus.store') }}" class="card max-w-2xl">
        @csrf
        @if ($quitus->exists)
            @method('PUT')
        @endif

        <div class="grid gap-3 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-select label="Étudiant" name="etudiant_id" :options="$etudiants->pluck('nom_complet', 'id')" :value="$quitus->etudiant_id" placeholder="Choisir…" required />
            </div>
            <x-input label="Année" name="annee" type="number" min="2000" max="2100" :value="$quitus->annee" required />
            <x-input label="Date du quitus" name="date_quitus" type="date" :value="$quitus->date_quitus?->format('Y-m-d')" />

            <div class="sm:col-span-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="en_retard" value="1" @checked(old('en_retard', $quitus->en_retard)) class="size-4 rounded accent-brand-600">
                    Dépôt en retard
                </label>
            </div>
            <x-input label="Frais de retard (Ar)" name="frais_retard" type="number" min="0" :value="$quitus->frais_retard" />
        </div>

        @unless ($quitus->exists)
            <p class="mt-3 text-xs text-muted">Le numéro est attribué automatiquement et les {{ count(\App\Models\Quitus::SERVICES_VISA) }} visas sont créés à l'enregistrement.</p>
        @endunless

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ $quitus->exists ? route('quitus.show', $quitus) : route('quitus.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>
