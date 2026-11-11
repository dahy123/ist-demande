<x-layouts.app :title="$etudiant->exists ? 'Modifier l\'étudiant' : 'Nouvel étudiant'">
    <form method="POST" action="{{ $etudiant->exists ? route('etudiants.update', $etudiant) : route('etudiants.store') }}" class="card max-w-4xl space-y-5">
        @csrf
        @if ($etudiant->exists)
            @method('PUT')
        @endif

        <section>
            <h2 class="text-sm font-semibold">État civil</h2>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                <x-input label="Nom" name="nom" :value="$etudiant->nom" maxlength="80" required />
                <x-input label="Prénoms" name="prenoms" :value="$etudiant->prenoms" maxlength="120" />
                <x-input label="Date de naissance" name="date_naissance" type="date" :value="$etudiant->date_naissance?->format('Y-m-d')" />
                <x-input label="Lieu de naissance" name="lieu_naissance" :value="$etudiant->lieu_naissance" maxlength="80" />
            </div>
        </section>

        <section class="border-t border-line pt-4">
            <h2 class="text-sm font-semibold">Coordonnées</h2>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                <x-input label="Téléphone" name="telephone" :value="$etudiant->telephone" maxlength="40" />
                <x-input label="E-mail" name="email" type="email" :value="$etudiant->email" maxlength="120" />
                <div class="sm:col-span-2">
                    <x-input label="Adresse" name="adresse" :value="$etudiant->adresse" maxlength="160" />
                </div>
            </div>
        </section>

        <section class="border-t border-line pt-4">
            <h2 class="text-sm font-semibold">Scolarité</h2>
            <div class="mt-3 grid gap-3 sm:grid-cols-3">
                <x-select label="École" name="ecole_id" :options="$ecoles->mapWithKeys(fn ($ecole) => [$ecole->id => $ecole->code])" :value="$etudiant->ecole_id" placeholder="—" />
                <x-input label="Mention" name="mention" :value="$etudiant->mention" maxlength="100" />
                <x-select label="Parcours" name="parcours" :options="$parcours->mapWithKeys(fn ($item) => [$item->code => $item->code.($item->libelle ? ' — '.$item->libelle : '')])" :value="$etudiant->parcours" placeholder="—" />
                <x-input label="Promotion" name="promotion" :value="$etudiant->promotion" maxlength="20" placeholder="ex. 2023-2026" />
                <x-select label="Diplôme visé" name="diplome_vise" :options="collect(\App\Enums\Diplome::cases())->mapWithKeys(fn ($diplome) => [$diplome->value => $diplome->label()])" :value="$etudiant->diplome_vise" placeholder="—" />
            </div>
        </section>

        <div class="flex justify-end gap-2">
            <a href="{{ $etudiant->exists ? route('etudiants.show', $etudiant) : route('etudiants.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>
