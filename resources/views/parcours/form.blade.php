<x-layouts.app :title="$parcours->exists ? 'Modifier le parcours' : 'Nouveau parcours'">
    @php($niveauxCoches = old('niveaux', $parcours->exists ? explode(',', $parcours->niveaux) : \App\Models\Parcours::NIVEAUX))

    <form method="POST" action="{{ $parcours->exists ? route('parcours.update', $parcours) : route('parcours.store') }}" class="card max-w-2xl">
        @csrf
        @if ($parcours->exists)
            @method('PUT')
        @endif

        <div class="grid gap-3 sm:grid-cols-2">
            <x-input label="Code" name="code" :value="$parcours->code" maxlength="20" required />
            <x-select label="École" name="ecole_id" :options="$ecoles->mapWithKeys(fn ($ecole) => [$ecole->id => $ecole->code.' — '.$ecole->nom])" :value="$parcours->ecole_id" placeholder="Choisir…" required />
            <div class="sm:col-span-2">
                <x-input label="Libellé" name="libelle" :value="$parcours->libelle" maxlength="120" />
            </div>
            <x-input label="Mention" name="mention" :value="$parcours->mention" maxlength="40" />

            <fieldset>
                <legend class="form-label">Niveaux ouverts</legend>
                <div class="flex flex-wrap gap-3 pt-1.5">
                    @foreach (\App\Models\Parcours::NIVEAUX as $niveau)
                        <label class="flex items-center gap-1.5 text-sm">
                            <input type="checkbox" name="niveaux[]" value="{{ $niveau }}" @checked(in_array($niveau, $niveauxCoches)) class="size-4 rounded accent-brand-600">
                            {{ $niveau }}
                        </label>
                    @endforeach
                </div>
                @error('niveaux')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </fieldset>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('parcours.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>
