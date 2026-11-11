<x-layouts.app title="Demandes">
    <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
        <form method="GET" class="flex flex-wrap items-end gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="N° ou nom de l'étudiant…" class="form-input w-64">
            <x-select name="type" :options="$types->pluck('libelle', 'id')" :value="request('type')" placeholder="Tous les types" class="w-48" />
            <x-select name="statut" :options="collect(\App\Enums\StatutDemande::cases())->mapWithKeys(fn ($statut) => [$statut->value => $statut->label()])" :value="request('statut')" placeholder="Tous les statuts" class="w-48" />
            <button type="submit" class="btn-secondary"><x-icon name="search" class="size-4" /> Filtrer</button>
        </form>
        <a href="{{ route('demandes.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouvelle demande</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Étudiant</th>
                        <th>Type</th>
                        <th>Dépôt</th>
                        <th>Agent</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($demandes as $demande)
                        <tr>
                            <td><a href="{{ route('demandes.show', $demande) }}" class="font-medium hover:text-brand-600">{{ $demande->numero }}</a></td>
                            <td>{{ $demande->etudiant->nom_complet }}</td>
                            <td>{{ $demande->typeDemande->libelle }}</td>
                            <td class="whitespace-nowrap text-muted">{{ $demande->date_depot->format('d/m/Y') }}</td>
                            <td>{{ $demande->agent?->nom_complet ?? '—' }}</td>
                            <td><span class="badge {{ $demande->statut->badgeClasses() }}">{{ $demande->statut->label() }}</span></td>
                            <td>
                                <div class="flex justify-end">
                                    <a href="{{ route('demandes.show', $demande) }}" class="row-action" aria-label="Ouvrir" title="Ouvrir"><x-icon name="eye" class="size-4" /></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-8 text-center text-muted">Aucune demande trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $demandes->links() }}</div>
</x-layouts.app>
