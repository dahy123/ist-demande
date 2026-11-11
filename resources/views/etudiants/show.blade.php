<x-layouts.app :title="$etudiant->nom_complet">
    <a href="{{ route('etudiants.index') }}" class="mb-3 inline-flex items-center gap-1.5 text-sm text-muted hover:text-ink"><x-icon name="arrow-left" class="size-4" /> Étudiants</a>

    <div class="grid gap-3 xl:grid-cols-3">
        {{-- Fiche --}}
        <div class="card h-fit">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <h2 class="font-semibold">{{ $etudiant->nom_complet }}</h2>
                    <p class="text-sm text-muted">{{ $etudiant->ecole?->nom ?? 'École non renseignée' }}</p>
                </div>
                <a href="{{ route('etudiants.edit', $etudiant) }}" class="row-action" aria-label="Modifier" title="Modifier"><x-icon name="pencil" class="size-4" /></a>
            </div>

            <dl class="mt-4 space-y-2 border-t border-line pt-4 text-sm">
                @foreach ([
                    'Né(e) le' => trim(($etudiant->date_naissance?->format('d/m/Y') ?? '').($etudiant->lieu_naissance ? ' à '.$etudiant->lieu_naissance : '')),
                    'Téléphone' => $etudiant->telephone,
                    'E-mail' => $etudiant->email,
                    'Adresse' => $etudiant->adresse,
                    'Mention' => $etudiant->mention,
                    'Parcours' => $etudiant->parcours,
                    'Promotion' => $etudiant->promotion,
                    'Diplôme visé' => $etudiant->diplome_vise?->label(),
                ] as $libelle => $valeur)
                    <div class="flex justify-between gap-4"><dt class="text-muted">{{ $libelle }}</dt><dd class="text-right font-medium">{{ $valeur ?: '—' }}</dd></div>
                @endforeach
            </dl>

            @unless ($etudiant->demandes->isNotEmpty() || $etudiant->quitus->isNotEmpty() || $etudiant->attestations->isNotEmpty())
                <div class="mt-4 border-t border-line pt-4">
                    <x-delete-button :action="route('etudiants.destroy', $etudiant)" confirm="Supprimer cet étudiant ?" label="Supprimer" />
                </div>
            @endunless
        </div>

        <div class="space-y-3 xl:col-span-2">
            {{-- Demandes --}}
            <div class="card">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold">Demandes</h2>
                    <a href="{{ route('demandes.create', ['etudiant' => $etudiant->id]) }}" class="btn-secondary"><x-icon name="plus" class="size-4" /> Nouvelle demande</a>
                </div>
                <ul class="mt-3 divide-y divide-line rounded-lg border border-line text-sm">
                    @forelse ($etudiant->demandes as $demande)
                        <li>
                            <a href="{{ route('demandes.show', $demande) }}" class="flex items-center justify-between gap-3 px-3 py-2 hover:bg-surface-2">
                                <span><span class="font-medium">{{ $demande->numero }}</span> · {{ $demande->typeDemande->libelle }}</span>
                                <span class="flex items-center gap-2">
                                    <span class="text-xs text-muted">{{ $demande->date_depot->format('d/m/Y') }}</span>
                                    <span class="badge {{ $demande->statut->badgeClasses() }}">{{ $demande->statut->label() }}</span>
                                </span>
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-6 text-center text-muted">Aucune demande.</li>
                    @endforelse
                </ul>
            </div>

            <div class="grid gap-3 lg:grid-cols-2">
                {{-- Quitus --}}
                <div class="card">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold">Quitus</h2>
                        <a href="{{ route('quitus.create', ['etudiant' => $etudiant->id]) }}" class="row-action" aria-label="Nouveau quitus" title="Nouveau quitus"><x-icon name="plus" class="size-4" /></a>
                    </div>
                    <ul class="mt-3 divide-y divide-line rounded-lg border border-line text-sm">
                        @forelse ($etudiant->quitus as $quitus)
                            <li><a href="{{ route('quitus.show', $quitus) }}" class="flex justify-between px-3 py-2 hover:bg-surface-2"><span class="font-medium">{{ $quitus->numero }}</span><span class="text-muted">{{ $quitus->annee }}</span></a></li>
                        @empty
                            <li class="px-3 py-6 text-center text-muted">Aucun quitus.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Attestations --}}
                <div class="card">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold">Attestations</h2>
                        <a href="{{ route('attestations.create', ['etudiant' => $etudiant->id]) }}" class="row-action" aria-label="Nouvelle attestation" title="Nouvelle attestation"><x-icon name="plus" class="size-4" /></a>
                    </div>
                    <ul class="mt-3 divide-y divide-line rounded-lg border border-line text-sm">
                        @forelse ($etudiant->attestations as $attestation)
                            <li><a href="{{ route('attestations.edit', $attestation) }}" class="flex justify-between px-3 py-2 hover:bg-surface-2"><span class="font-medium">{{ $attestation->numero }}</span><span class="text-muted">{{ $attestation->diplome->label() }} · {{ $attestation->version->label() }}</span></a></li>
                        @empty
                            <li class="px-3 py-6 text-center text-muted">Aucune attestation.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
