<x-layouts.app title="Nouveau type de demande">
    <form method="POST" action="{{ route('types-demande.store') }}" class="card max-w-2xl">
        @csrf

        <div class="grid gap-3 sm:grid-cols-2">
            <x-input label="Code" name="code" placeholder="ex. RELEVE" maxlength="30" required />
            <x-input label="Délai cible (jours)" name="delai_cible_jours" type="number" min="1" max="365" />
            <div class="sm:col-span-2">
                <x-input label="Libellé" name="libelle" maxlength="80" required />
            </div>
        </div>

        <p class="mt-3 text-xs text-muted">Les tarifs et les pièces requises s'ajoutent ensuite sur la fiche du type.</p>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('types-demande.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Créer</button>
        </div>
    </form>
</x-layouts.app>
