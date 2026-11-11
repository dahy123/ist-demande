<x-layouts.app :title="'Quitus '.$quitus->numero">
    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
        <a href="{{ route('quitus.index') }}" class="inline-flex items-center gap-1.5 text-sm text-muted hover:text-ink"><x-icon name="arrow-left" class="size-4" /> Quitus</a>
        <div class="flex items-center gap-2">
            <a href="{{ route('quitus.edit', $quitus) }}" class="btn-secondary"><x-icon name="pencil" class="size-4" /> Modifier</a>
            <x-delete-button :action="route('quitus.destroy', $quitus)" confirm="Supprimer le quitus {{ $quitus->numero }} et ses visas ?" label="Supprimer" />
        </div>
    </div>

    <div class="grid gap-3 xl:grid-cols-3">
        <div class="card h-fit">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs text-muted">Quitus {{ $quitus->annee }}</p>
                    <h2 class="text-lg font-semibold">{{ $quitus->numero }}</h2>
                </div>
                @if ($quitus->estComplet())
                    <span class="badge bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Complet</span>
                @else
                    <span class="badge bg-amber-500/10 text-amber-600 dark:text-amber-400">En cours</span>
                @endif
            </div>
            <dl class="mt-4 space-y-2 border-t border-line pt-4 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-muted">Étudiant</dt><dd class="text-right font-medium"><a href="{{ route('etudiants.show', $quitus->etudiant) }}" class="hover:text-brand-600">{{ $quitus->etudiant->nom_complet }}</a></dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">École</dt><dd class="font-medium">{{ $quitus->etudiant->ecole?->code ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Date</dt><dd class="font-medium">{{ $quitus->date_quitus?->format('d/m/Y') ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Frais de retard</dt><dd class="font-medium">{{ $quitus->en_retard ? number_format($quitus->frais_retard, 0, ',', ' ').' Ar' : 'Non' }}</dd></div>
            </dl>
        </div>

        <div class="card xl:col-span-2">
            <h2 class="text-sm font-semibold">Visas des services</h2>
            <p class="text-xs text-muted">Le quitus est complet quand chaque service a donné son visa.</p>

            <div class="mt-3 space-y-2">
                @foreach ($quitus->visas as $visa)
                    @php($bag = 'visa'.$visa->id)
                    <form method="POST" action="{{ route('quitus-visas.update', $visa) }}" @class([
                        'grid gap-3 rounded-lg border p-3 sm:grid-cols-12 sm:items-end',
                        'border-emerald-500/30 bg-emerald-500/5' => $visa->valide,
                        'border-line' => ! $visa->valide,
                    ])>
                        @csrf
                        @method('PUT')
                        <div class="sm:col-span-3">
                            <p class="text-xs text-muted">{{ $visa->position }}.</p>
                            <p class="text-sm font-medium">{{ $visa->service }}</p>
                            @if ($visa->date_visa)
                                <p class="text-xs text-emerald-600 dark:text-emerald-400">Visé le {{ $visa->date_visa->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <div class="sm:col-span-3"><x-input label="Responsable" name="responsable" :id="'responsable-'.$visa->id" :value="$visa->responsable" maxlength="100" :bag="$bag" /></div>
                        <div class="sm:col-span-3"><x-input label="Observation" name="observation" :id="'observation-'.$visa->id" :value="$visa->observation" maxlength="160" :bag="$bag" /></div>
                        <div class="flex items-center justify-between gap-2 sm:col-span-3">
                            <label class="flex items-center gap-1.5 text-sm">
                                <input type="hidden" name="valide" value="0">
                                <input type="checkbox" name="valide" value="1" @checked($visa->valide) class="size-4 rounded accent-brand-600"> Visé
                            </label>
                            <button type="submit" class="btn-secondary">OK</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
