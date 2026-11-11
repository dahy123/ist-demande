<x-layouts.app :title="$typeDemande->libelle">
    @php($diplomes = collect(\App\Enums\Diplome::cases())->mapWithKeys(fn ($diplome) => [$diplome->value => $diplome->label()]))

    <a href="{{ route('types-demande.index') }}" class="mb-3 inline-flex items-center gap-1.5 text-sm text-muted hover:text-ink"><x-icon name="arrow-left" class="size-4" /> Types de demande</a>

    <div class="grid gap-3 xl:grid-cols-3">
        {{-- Informations générales --}}
        <form method="POST" action="{{ route('types-demande.update', $typeDemande) }}" class="card h-fit">
            @csrf
            @method('PUT')

            <h2 class="text-sm font-semibold">Informations</h2>
            <div class="mt-3 space-y-3">
                <x-input label="Code" name="code" :value="$typeDemande->code" maxlength="30" required />
                <x-input label="Libellé" name="libelle" :value="$typeDemande->libelle" maxlength="80" required />
                <x-input label="Délai cible (jours)" name="delai_cible_jours" type="number" min="1" max="365" :value="$typeDemande->delai_cible_jours" />
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>

        <div class="space-y-3 xl:col-span-2">
            {{-- Tarifs --}}
            <div class="card">
                <h2 class="text-sm font-semibold">Tarifs</h2>
                <p class="text-xs text-muted">Un tarif sans diplôme s'applique à tous ; un tarif propre à un diplôme est prioritaire.</p>

                <div class="mt-3 overflow-x-auto rounded-lg border border-line">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Diplôme</th>
                                <th class="text-right">Montant</th>
                                <th class="text-right">En retard</th>
                                <th>Période</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($typeDemande->tarifs as $tarif)
                                <tr>
                                    <td>{{ $tarif->niveau_diplome?->label() ?? 'Tous' }}</td>
                                    <td class="text-right">{{ number_format($tarif->montant, 0, ',', ' ') }} Ar</td>
                                    <td class="text-right">{{ $tarif->montant_retard !== null ? number_format($tarif->montant_retard, 0, ',', ' ').' Ar' : '—' }}</td>
                                    <td class="text-xs text-muted">Du {{ $tarif->date_debut->format('d/m/Y') }}{{ $tarif->date_fin ? ' au '.$tarif->date_fin->format('d/m/Y') : '' }}</td>
                                    <td class="text-right"><x-delete-button :action="route('tarifs.destroy', $tarif)" confirm="Supprimer ce tarif ?" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-6 text-center text-muted">Aucun tarif : la demande est gratuite.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route('types-demande.tarifs.store', $typeDemande) }}" class="mt-3 grid gap-3 sm:grid-cols-6 sm:items-end">
                    @csrf
                    <div class="sm:col-span-2"><x-select label="Diplôme" name="niveau_diplome" :options="$diplomes" placeholder="Tous" bag="tarif" /></div>
                    <x-input label="Montant (Ar)" name="montant" type="number" min="0" bag="tarif" required />
                    <x-input label="En retard (Ar)" name="montant_retard" type="number" min="0" bag="tarif" />
                    <x-input label="Début" name="date_debut" type="date" :value="today()->format('Y-m-d')" bag="tarif" required />
                    <x-input label="Fin" name="date_fin" type="date" bag="tarif" />
                    <div class="sm:col-span-6 flex justify-end">
                        <button type="submit" class="btn-secondary"><x-icon name="plus" class="size-4" /> Ajouter le tarif</button>
                    </div>
                </form>
            </div>

            {{-- Pièces requises --}}
            <div class="card">
                <h2 class="text-sm font-semibold">Pièces requises</h2>
                <p class="text-xs text-muted">Elles sont ajoutées automatiquement au dossier de chaque nouvelle demande.</p>

                <ul class="mt-3 divide-y divide-line rounded-lg border border-line">
                    @forelse ($typeDemande->piecesRequises as $piece)
                        <li class="flex items-center justify-between gap-3 px-3 py-2 text-sm">
                            <span>
                                {{ $piece->libelle }}
                                @unless ($piece->obligatoire)
                                    <span class="badge ml-1 bg-surface-2 text-muted">Facultative</span>
                                @endunless
                            </span>
                            <x-delete-button :action="route('pieces-requises.destroy', $piece)" confirm="Supprimer cette pièce ?" />
                        </li>
                    @empty
                        <li class="px-3 py-6 text-center text-sm text-muted">Aucune pièce requise.</li>
                    @endforelse
                </ul>

                <form method="POST" action="{{ route('types-demande.pieces.store', $typeDemande) }}" class="mt-3 flex flex-wrap items-end gap-3">
                    @csrf
                    <div class="min-w-60 flex-1"><x-input label="Libellé de la pièce" name="libelle" maxlength="160" bag="piece" required /></div>
                    <label class="flex items-center gap-2 pb-2 text-sm">
                        <input type="checkbox" name="obligatoire" value="1" checked class="size-4 rounded accent-brand-600"> Obligatoire
                    </label>
                    <button type="submit" class="btn-secondary"><x-icon name="plus" class="size-4" /> Ajouter</button>
                </form>
            </div>

            @if ($typeDemande->demandes()->doesntExist())
                <div class="flex justify-end">
                    <x-delete-button :action="route('types-demande.destroy', $typeDemande)" confirm="Supprimer ce type de demande ?" label="Supprimer le type" />
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
