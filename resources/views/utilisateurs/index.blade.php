<x-layouts.app title="Utilisateurs">
    <div class="mb-3 flex justify-end">
        <a href="{{ route('utilisateurs.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Nouvel utilisateur</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Identifiant</th>
                        <th>Service</th>
                        <th>Rôles</th>
                        <th>État</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($utilisateurs as $utilisateur)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <span class="grid size-8 shrink-0 place-items-center rounded-md bg-brand-100 text-[11px] font-semibold text-brand-700 dark:bg-brand-500/20 dark:text-brand-300">{{ $utilisateur->initiales }}</span>
                                    <div class="leading-tight">
                                        <p class="font-medium">{{ $utilisateur->nom_complet }}</p>
                                        <p class="text-xs text-muted">{{ $utilisateur->email ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $utilisateur->identifiant }}</td>
                            <td>{{ $utilisateur->service ?? '—' }}</td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($utilisateur->roles as $role)
                                        <span class="badge bg-surface-2 text-muted">{{ $role->libelle }}</span>
                                    @empty
                                        <span class="text-subtle">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                @if ($utilisateur->actif)
                                    <span class="badge bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Actif</span>
                                @else
                                    <span class="badge bg-red-500/10 text-red-600 dark:text-red-400">Désactivé</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('utilisateurs.edit', $utilisateur) }}" class="row-action" aria-label="Modifier" title="Modifier"><x-icon name="pencil" class="size-4" /></a>
                                    @unless ($utilisateur->is(auth()->user()))
                                        <x-delete-button :action="route('utilisateurs.destroy', $utilisateur)" confirm="Supprimer le compte {{ $utilisateur->identifiant }} ?" />
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
