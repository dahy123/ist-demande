<x-layouts.app title="Types de demande">
    <div class="mb-3 flex justify-end">
        <a href="{{ route('types-demande.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouveau type</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        <th class="text-right">Délai cible</th>
                        <th class="text-right">Tarifs</th>
                        <th class="text-right">Pièces requises</th>
                        <th class="text-right">Demandes</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $type)
                        <tr>
                            <td class="font-medium">{{ $type->code }}</td>
                            <td>{{ $type->libelle }}</td>
                            <td class="text-right">{{ $type->delai_cible_jours ? $type->delai_cible_jours.' j' : '—' }}</td>
                            <td class="text-right">{{ $type->tarifs_count }}</td>
                            <td class="text-right">{{ $type->pieces_requises_count }}</td>
                            <td class="text-right">{{ $type->demandes_count }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('types-demande.show', $type) }}" class="row-action" aria-label="Ouvrir" title="Ouvrir"><x-icon name="eye" class="size-4" /></a>
                                    @if ($type->demandes_count === 0)
                                        <x-delete-button :action="route('types-demande.destroy', $type)" confirm="Supprimer le type {{ $type->libelle }} ?" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-8 text-center text-muted">Aucun type de demande.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
