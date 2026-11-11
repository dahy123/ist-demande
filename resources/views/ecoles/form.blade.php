<x-layouts.app :title="$ecole->exists ? 'Modifier l\'école' : 'Nouvelle école'">
    <form method="POST" action="{{ $ecole->exists ? route('ecoles.update', $ecole) : route('ecoles.store') }}" class="card max-w-2xl">
        @csrf
        @if ($ecole->exists)
            @method('PUT')
        @endif

        <div class="grid gap-3 sm:grid-cols-3">
            <x-input label="Code" name="code" :value="$ecole->code" maxlength="10" required />
            <div class="sm:col-span-2">
                <x-input label="Nom" name="nom" :value="$ecole->nom" maxlength="120" required />
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('ecoles.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>
