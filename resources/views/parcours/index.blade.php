<x-layouts.app title="Parcours">
    <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
        <form method="GET" class="flex items-end gap-2">
            <x-select name="ecole" :options="$ecoles->pluck('code', 'id')" :value="request('ecole')" placeholder="Toutes les écoles" onchange="this.form.submit()" />
        </form>
        <a href="{{ route('parcours.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouveau parcours</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        <th>École</th>
                        <th>Mention</th>
                        <th>Niveaux</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parcours as $item)
                        <tr>
                            <td class="font-medium">{{ $item->code }}</td>
                            <td>{{ $item->libelle ?? '—' }}</td>
                            <td>{{ $item->ecole->code }}</td>
                            <td>{{ $item->mention ?? '—' }}</td>
                            <td class="text-xs text-muted">{{ str_replace(',', ' · ', $item->niveaux) }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('parcours.edit', $item) }}" class="row-action" aria-label="Modifier" title="Modifier"><x-icon name="pencil" class="size-4" /></a>
                                    <x-delete-button :action="route('parcours.destroy', $item)" confirm="Supprimer le parcours {{ $item->code }} ?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-muted">Aucun parcours.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
