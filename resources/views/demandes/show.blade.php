<x-layouts.app :title="'Demande '.$demande->numero">
    @php
        $montantPaye = $demande->montantPaye();
        $statuts = collect(\App\Enums\StatutDemande::cases())->mapWithKeys(fn ($statut) => [$statut->value => $statut->label()]);
        $justificatifs = collect(\App\Enums\TypeJustificatif::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]);
        $ar = fn (?int $montant) => $montant === null ? '—' : number_format($montant, 0, ',', ' ').' Ar';
    @endphp

    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
        <a href="{{ route('demandes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-muted hover:text-ink"><x-icon name="arrow-left" class="size-4" /> Demandes</a>
        <div class="flex items-center gap-2">
            <a href="{{ route('demandes.edit', $demande) }}" class="btn-secondary"><x-icon name="pencil" class="size-4" /> Modifier</a>
            @if ($demande->statut === \App\Enums\StatutDemande::Enregistree)
                <x-delete-button :action="route('demandes.destroy', $demande)" confirm="Supprimer la demande {{ $demande->numero }} ?" label="Supprimer" />
            @endif
        </div>
    </div>

    <div class="grid gap-3 xl:grid-cols-3">
        <div class="space-y-3 xl:col-span-2">
            {{-- Résumé --}}
            <div class="card">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <p class="text-xs text-muted">{{ $demande->typeDemande->libelle }}</p>
                        <h2 class="text-lg font-semibold">{{ $demande->numero }}</h2>
                    </div>
                    <span class="badge {{ $demande->statut->badgeClasses() }} text-sm">{{ $demande->statut->label() }}</span>
                </div>

                <dl class="mt-4 grid gap-x-6 gap-y-2 border-t border-line pt-4 text-sm sm:grid-cols-2">
                    <div class="flex justify-between gap-4"><dt class="text-muted">Étudiant</dt><dd class="text-right font-medium"><a href="{{ route('etudiants.show', $demande->etudiant) }}" class="hover:text-brand-600">{{ $demande->etudiant->nom_complet }}</a></dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">École</dt><dd class="font-medium">{{ $demande->etudiant->ecole?->code ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Niveau / parcours</dt><dd class="font-medium">{{ collect([$demande->niveau, $demande->parcours])->filter()->join(' · ') ?: '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Année universitaire</dt><dd class="font-medium">{{ $demande->annee_universitaire ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Déposée le</dt><dd class="font-medium">{{ $demande->date_depot->format('d/m/Y H:i') }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Enregistrée par</dt><dd class="font-medium">{{ $demande->creePar?->nom_complet ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Agent traitant</dt><dd class="font-medium">{{ $demande->agent?->nom_complet ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Mode de retrait</dt><dd class="font-medium">{{ \App\Models\Demande::MODES_RETRAIT[$demande->mode_retrait] ?? '—' }}</dd></div>
                    @if ($demande->typeDemande->delai_cible_jours)
                        <div class="flex justify-between gap-4"><dt class="text-muted">Échéance cible</dt><dd class="font-medium">{{ $demande->date_depot->copy()->addDays($demande->typeDemande->delai_cible_jours)->format('d/m/Y') }}</dd></div>
                    @endif
                    @if ($demande->date_livraison)
                        <div class="flex justify-between gap-4"><dt class="text-muted">Livrée le</dt><dd class="font-medium">{{ $demande->date_livraison->format('d/m/Y') }}</dd></div>
                    @endif
                </dl>

                @if ($demande->observation)
                    <p class="mt-4 rounded-lg bg-surface-2 p-3 text-sm whitespace-pre-line">{{ $demande->observation }}</p>
                @endif
            </div>

            {{-- Pièces du dossier --}}
            <div class="card">
                <h2 class="text-sm font-semibold">Pièces du dossier</h2>
                <ul class="mt-3 divide-y divide-line rounded-lg border border-line">
                    @forelse ($demande->piecesFournies as $piece)
                        <li class="flex items-center justify-between gap-3 px-3 py-2 text-sm">
                            <span class="flex items-center gap-2">
                                <span @class([
                                    'grid size-5 shrink-0 place-items-center rounded-full',
                                    'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' => $piece->fournie,
                                    'bg-surface-2 text-subtle' => ! $piece->fournie,
                                ])><x-icon :name="$piece->fournie ? 'check' : 'x'" class="size-3" /></span>
                                {{ $piece->pieceRequise->libelle }}
                                @unless ($piece->pieceRequise->obligatoire)
                                    <span class="badge bg-surface-2 text-muted">Facultative</span>
                                @endunless
                            </span>
                            <form method="POST" action="{{ route('pieces-fournies.update', $piece) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                @if ($piece->fournie)
                                    <span class="text-xs text-muted">{{ $piece->date_fourniture?->format('d/m/Y') }}</span>
                                    <input type="hidden" name="fournie" value="0">
                                    <button type="submit" class="text-xs text-muted hover:text-red-500">Annuler</button>
                                @else
                                    <input type="hidden" name="fournie" value="1">
                                    <button type="submit" class="text-xs font-medium text-brand-600 hover:underline">Marquer fournie</button>
                                @endif
                            </form>
                        </li>
                    @empty
                        <li class="px-3 py-6 text-center text-sm text-muted">Aucune pièce requise pour ce type de demande.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Paiements --}}
            <div class="card">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h2 class="text-sm font-semibold">Paiements</h2>
                    <p class="text-sm">
                        <span class="text-muted">Payé</span> <span class="font-semibold">{{ $ar($montantPaye) }}</span>
                        <span class="text-muted">/ attendu</span> <span class="font-semibold">{{ $montantAttendu === null ? 'gratuit' : $ar($montantAttendu) }}</span>
                    </p>
                </div>

                @if ($demande->paiements->isNotEmpty())
                    <div class="mt-3 overflow-x-auto rounded-lg border border-line">
                        <table class="data-table">
                            <thead>
                                <tr><th>Justificatif</th><th>N°</th><th>Date</th><th class="text-right">Montant</th><th>Saisi par</th><th></th></tr>
                            </thead>
                            <tbody>
                                @foreach ($demande->paiements as $paiement)
                                    <tr>
                                        <td>{{ $paiement->type_justificatif->label() }}</td>
                                        <td class="font-medium">{{ $paiement->numero }}/{{ $paiement->annee }}</td>
                                        <td class="text-muted">{{ $paiement->date_paiement?->format('d/m/Y') ?? '—' }}</td>
                                        <td class="text-right">{{ $ar($paiement->montant_paye) }}</td>
                                        <td class="text-muted">{{ $paiement->saisiPar?->nom_complet ?? '—' }}</td>
                                        <td class="text-right"><x-delete-button :action="route('paiements.destroy', $paiement)" confirm="Supprimer ce paiement ?" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <form method="POST" action="{{ route('demandes.paiements.store', $demande) }}" class="mt-3 grid gap-3 sm:grid-cols-5 sm:items-end">
                    @csrf
                    <x-select label="Justificatif" name="type_justificatif" :options="$justificatifs" bag="paiement" required />
                    <x-input label="N° du justificatif" name="numero" maxlength="30" bag="paiement" required />
                    <x-input label="Année" name="annee" type="number" :value="now()->year" bag="paiement" required />
                    <x-input label="Date" name="date_paiement" type="date" :value="today()->format('Y-m-d')" bag="paiement" />
                    <x-input label="Montant (Ar)" name="montant_paye" type="number" min="1" :value="$montantAttendu !== null ? max($montantAttendu - $montantPaye, 0) ?: null : null" bag="paiement" required />
                    <div class="flex justify-end sm:col-span-5">
                        <button type="submit" class="btn-secondary"><x-icon name="plus" class="size-4" /> Enregistrer le paiement</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-3">
            {{-- Changement de statut --}}
            <form method="POST" action="{{ route('demandes.statut', $demande) }}" class="card">
                @csrf
                @method('PUT')

                <h2 class="text-sm font-semibold">Faire avancer la demande</h2>
                <div class="mt-3 space-y-3">
                    <x-select label="Nouveau statut" name="statut" :options="$statuts" :value="$demande->statut" bag="statut" required />
                    <x-select label="Agent traitant" name="agent_id" :options="$agents->pluck('nom_complet', 'id')" :value="$demande->agent_id" placeholder="—" bag="statut" />
                    <x-input label="Commentaire" name="commentaire" maxlength="200" bag="statut" placeholder="Obligatoire en cas de refus" />
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn-primary">Valider</button>
                </div>
            </form>

            {{-- Historique --}}
            <div class="card">
                <h2 class="text-sm font-semibold">Historique</h2>
                <ol class="mt-3 space-y-3 border-l border-line pl-4">
                    @foreach ($demande->historique as $etape)
                        <li class="relative text-sm">
                            <span class="absolute top-1.5 -left-[21px] size-2.5 rounded-full border-2 border-surface bg-brand-500"></span>
                            <p><span class="badge {{ $etape->nouveau_statut->badgeClasses() }}">{{ $etape->nouveau_statut->label() }}</span></p>
                            <p class="mt-1 text-xs text-muted">{{ $etape->date_heure->format('d/m/Y H:i') }} · {{ $etape->utilisateur?->nom_complet ?? 'Système' }}</p>
                            @if ($etape->commentaire)
                                <p class="mt-1 text-xs">{{ $etape->commentaire }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</x-layouts.app>
