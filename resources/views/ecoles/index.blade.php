<x-layouts.app title="Écoles">
    <div class="mb-3 flex justify-end">
        <a href="{{ route('ecoles.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouvelle école</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th class="text-right">Parcours</th>
                        <th class="text-right">Étudiants</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ecoles as $ecole)
                        <tr>
                            <td class="font-medium">{{ $ecole->code }}</td>
                            <td>{{ $ecole->nom }}</td>
                            <td class="text-right">{{ $ecole->parcours_count }}</td>
                            <td class="text-right">{{ $ecole->etudiants_count }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('ecoles.edit', $ecole) }}" class="row-action" aria-label="Modifier" title="Modifier"><x-icon name="pencil" class="size-4" /></a>
                                    <x-delete-button :action="route('ecoles.destroy', $ecole)" confirm="Supprimer l'école {{ $ecole->code }} ?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-muted">Aucune école enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
