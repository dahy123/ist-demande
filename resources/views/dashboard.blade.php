<x-layouts.app title="Tableau de bord">
    @php
        $tones = [
            'indigo' => 'bg-brand-500/10 text-brand-600 dark:text-brand-300',
            'amber' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
            'emerald' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
            'sky' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400',
        ];
        $maxMois = max($mois->max(), 1);
        $nomsMois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    @endphp

    {{-- Indicateurs --}}
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="card flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-lg {{ $tones[$stat['tone']] }}">
                    <x-icon :name="$stat['icon']" />
                </span>
                <div>
                    <p class="text-xs text-muted">{{ $stat['label'] }}</p>
                    <p class="text-xl font-semibold">{{ number_format($stat['value'], 0, ',', ' ') }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3 grid gap-3 xl:grid-cols-3">
        {{-- Demandes déposées par mois --}}
        <div class="card xl:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold">Évolution des demandes</h2>
                <span class="rounded-md bg-surface-2 px-2 py-0.5 text-xs text-muted">{{ now()->year }}</span>
            </div>

            <div class="mt-4 flex h-56 items-end gap-1.5 sm:gap-3">
                @foreach ($mois as $numero => $total)
                    <div class="flex h-full flex-1 flex-col items-center justify-end gap-1" title="{{ $nomsMois[$numero - 1] }} : {{ $total }} demande(s)">
                        <span class="text-[11px] text-muted">{{ $total ?: '' }}</span>
                        <div @class([
                            'w-full max-w-10 rounded-t-md',
                            'bg-brand-500' => $numero === now()->month,
                            'bg-brand-500/40' => $numero !== now()->month,
                        ]) style="height: {{ $total ? max(round($total / $maxMois * 100), 3) : 0 }}%"></div>
                        <span class="text-[11px] text-subtle">{{ $nomsMois[$numero - 1] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Dernières demandes --}}
        <div class="card">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold">Dernières demandes</h2>
                <a href="{{ route('demandes.index') }}" class="text-xs text-brand-600 hover:underline">Tout voir</a>
            </div>

            @if ($dernieresDemandes->isEmpty())
                <div class="mt-4 grid h-56 place-items-center rounded-lg border border-dashed border-line-strong text-center text-sm text-subtle">
                    <div>
                        <x-icon name="inbox" class="mx-auto mb-2 size-7" />
                        Aucune demande pour le moment.
                    </div>
                </div>
            @else
                <ul class="mt-3 divide-y divide-line">
                    @foreach ($dernieresDemandes as $demande)
                        <li>
                            <a href="{{ route('demandes.show', $demande) }}" class="flex items-center justify-between gap-3 py-2 text-sm hover:text-brand-600">
                                <span class="min-w-0">
                                    <span class="block truncate font-medium">{{ $demande->etudiant->nom_complet }}</span>
                                    <span class="block truncate text-xs text-muted">{{ $demande->numero }} · {{ $demande->typeDemande->libelle }}</span>
                                </span>
                                <span class="badge {{ $demande->statut->badgeClasses() }}">{{ $demande->statut->label() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>
