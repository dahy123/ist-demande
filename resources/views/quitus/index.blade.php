<x-layouts.app title="Quitus">
    <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
        <form method="GET" class="flex flex-wrap items-end gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="N° ou nom de l'étudiant…" class="form-input w-64">
            <x-select name="annee" :options="$annees->combine($annees)" :value="request('annee')" placeholder="Toutes les années" class="w-44" />
            <button type="submit" class="btn-secondary"><x-icon name="search" class="size-4" /> Filtrer</button>
        </form>
        <a href="{{ route('quitus.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouveau quitus</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Étudiant</th>
                        <th>Date</th>
                        <th>Visas</th>
                        <th>Retard</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quitus as $item)
                        @php($visasValides = $item->visas->where('valide', true)->count())
                        <tr>
                            <td><a href="{{ route('quitus.show', $item) }}" class="font-medium hover:text-brand-600">{{ $item->numero }}</a></td>
                            <td>{{ $item->etudiant->nom_complet }}</td>
                            <td class="text-muted">{{ $item->date_quitus?->format('d/m/Y') ?? '—' }}</td>
                            <td>
                                <span @class([
                                    'badge',
                                    'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' => $item->estComplet(),
                                    'bg-amber-500/10 text-amber-600 dark:text-amber-400' => ! $item->estComplet(),
                                ])>{{ $visasValides }} / {{ $item->visas->count() }}</span>
                            </td>
                            <td>{{ $item->en_retard ? number_format($item->frais_retard, 0, ',', ' ').' Ar' : '—' }}</td>
                            <td>
                                <div class="flex justify-end">
                                    <a href="{{ route('quitus.show', $item) }}" class="row-action" aria-label="Ouvrir" title="Ouvrir"><x-icon name="eye" class="size-4" /></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-muted">Aucun quitus trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $quitus->links() }}</div>
</x-layouts.app>
