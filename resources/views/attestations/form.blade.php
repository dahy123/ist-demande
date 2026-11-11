<x-layouts.app :title="$attestation->exists ? 'Modifier l\'attestation '.$attestation->numero : 'Nouvelle attestation'">
    <form method="POST" action="{{ $attestation->exists ? route('attestations.update', $attestation) : route('attestations.store') }}" class="card max-w-2xl">
        @csrf
        @if ($attestation->exists)
            @method('PUT')
        @endif

        <div class="grid gap-3 sm:grid-cols-2">
            <x-input label="Numéro" name="numero" :value="$attestation->numero" maxlength="20" required />
            <x-select label="Étudiant" name="etudiant_id" :options="$etudiants->pluck('nom_complet', 'id')" :value="$attestation->etudiant_id" placeholder="Choisir…" required />
            <x-select label="Diplôme" name="diplome" :options="collect(\App\Enums\Diplome::cases())->mapWithKeys(fn ($diplome) => [$diplome->value => $diplome->label()])" :value="$attestation->diplome" placeholder="Choisir…" required />
            <x-select label="Version" name="version" :options="collect(\App\Enums\VersionAttestation::cases())->mapWithKeys(fn ($version) => [$version->value => $version->label()])" :value="$attestation->version" required />
            <x-input label="Date de délibération" name="date_deliberation" type="date" :value="$attestation->date_deliberation?->format('Y-m-d')" />
            <x-input label="Date de retrait" name="date_retrait" type="date" :value="$attestation->date_retrait?->format('Y-m-d')" />
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('attestations.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>
