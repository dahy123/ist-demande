<x-layouts.app title="Attestations">
    <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
        <form method="GET" class="flex flex-wrap items-end gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="N° ou nom de l'étudiant…" class="form-input w-64">
            <x-select name="version" :options="collect(\App\Enums\VersionAttestation::cases())->mapWithKeys(fn ($version) => [$version->value => $version->label()])" :value="request('version')" placeholder="Toutes les versions" class="w-44" />
            <button type="submit" class="btn-secondary"><x-icon name="search" class="size-4" /> Filtrer</button>
        </form>
        <a href="{{ route('attestations.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouvelle attestation</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Étudiant</th>
                        <th>Diplôme</th>
                        <th>Délibération</th>
                        <th>Version</th>
                        <th>Retrait</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attestations as $attestation)
                        <tr>
                            <td class="font-medium">{{ $attestation->numero }}</td>
                            <td><a href="{{ route('etudiants.show', $attestation->etudiant) }}" class="hover:text-brand-600">{{ $attestation->etudiant->nom_complet }}</a></td>
                            <td>{{ $attestation->diplome->label() }}</td>
                            <td class="text-muted">{{ $attestation->date_deliberation?->format('d/m/Y') ?? '—' }}</td>
                            <td>
                                <span @class([
                                    'badge',
                                    'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' => $attestation->version === \App\Enums\VersionAttestation::Originale,
                                    'bg-surface-2 text-muted' => $attestation->version === \App\Enums\VersionAttestation::Provisoire,
                                ])>{{ $attestation->version->label() }}</span>
                            </td>
                            <td class="text-muted">{{ $attestation->date_retrait?->format('d/m/Y') ?? 'Non retirée' }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('attestations.edit', $attestation) }}" class="row-action" aria-label="Modifier" title="Modifier"><x-icon name="pencil" class="size-4" /></a>
                                    <x-delete-button :action="route('attestations.destroy', $attestation)" confirm="Supprimer l'attestation {{ $attestation->numero }} ?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-8 text-center text-muted">Aucune attestation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $attestations->links() }}</div>
</x-layouts.app>
