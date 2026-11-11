<x-layouts.app title="Étudiants">
    <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
        <form method="GET" class="flex flex-wrap items-end gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, prénoms, e-mail…" class="form-input w-64">
            <x-select name="ecole" :options="$ecoles->pluck('code', 'id')" :value="request('ecole')" placeholder="Toutes les écoles" class="w-44" />
            <button type="submit" class="btn-secondary"><x-icon name="search" class="size-4" /> Filtrer</button>
        </form>
        <a href="{{ route('etudiants.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouvel étudiant</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom et prénoms</th>
                        <th>École</th>
                        <th>Parcours</th>
                        <th>Promotion</th>
                        <th>Contact</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($etudiants as $etudiant)
                        <tr>
                            <td><a href="{{ route('etudiants.show', $etudiant) }}" class="font-medium hover:text-brand-600">{{ $etudiant->nom_complet }}</a></td>
                            <td>{{ $etudiant->ecole?->code ?? '—' }}</td>
                            <td>{{ $etudiant->parcours ?? '—' }}</td>
                            <td>{{ $etudiant->promotion ?? '—' }}</td>
                            <td class="text-xs text-muted">{{ $etudiant->telephone ?? $etudiant->email ?? '—' }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('etudiants.show', $etudiant) }}" class="row-action" aria-label="Ouvrir" title="Ouvrir"><x-icon name="eye" class="size-4" /></a>
                                    <a href="{{ route('etudiants.edit', $etudiant) }}" class="row-action" aria-label="Modifier" title="Modifier"><x-icon name="pencil" class="size-4" /></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-muted">Aucun étudiant trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $etudiants->links() }}</div>
</x-layouts.app>
