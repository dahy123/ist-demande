<x-layouts.app title="Journal d'audit">
    <form method="GET" class="mb-3 flex flex-wrap items-end gap-2">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Action ou détail…" class="form-input w-64">
        <x-select name="utilisateur" :options="$utilisateurs->pluck('nom_complet', 'id')" :value="request('utilisateur')" placeholder="Tous les utilisateurs" class="w-52" />
        <button type="submit" class="btn-secondary"><x-icon name="search" class="size-4" /> Filtrer</button>
    </form>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date et heure</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Détail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entrees as $entree)
                        <tr>
                            <td class="whitespace-nowrap text-muted">{{ $entree->date_heure->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $entree->utilisateur?->nom_complet ?? 'Système' }}</td>
                            <td>
                                <span @class([
                                    'badge',
                                    'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' => $entree->action === 'Création',
                                    'bg-amber-500/10 text-amber-600 dark:text-amber-400' => $entree->action === 'Modification',
                                    'bg-red-500/10 text-red-600 dark:text-red-400' => $entree->action === 'Suppression',
                                    'bg-surface-2 text-muted' => ! in_array($entree->action, ['Création', 'Modification', 'Suppression']),
                                ])>{{ $entree->action }}</span>
                            </td>
                            <td>{{ $entree->detail ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-muted">Aucune entrée dans le journal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $entrees->links() }}</div>
</x-layouts.app>
