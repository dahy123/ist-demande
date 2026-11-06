<x-layouts.app title="Tableau de bord">
    @php
        $tones = [
            'indigo' => 'bg-brand-500/10 text-brand-600 dark:text-brand-300',
            'amber' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
            'emerald' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
            'sky' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400',
        ];
    @endphp

    <p class="mb-4 text-sm text-muted">Bonjour {{ auth()->user()->prenoms ?: auth()->user()->nom }}, voici l'activité du jour.</p>

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
        {{-- Graphique (emplacement) --}}
        <div class="card xl:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold">Évolution des demandes</h2>
                <span class="rounded-md bg-surface-2 px-2 py-0.5 text-xs text-muted">Cette année</span>
            </div>
            <div class="mt-4 grid h-56 place-items-center rounded-lg border border-dashed border-line-strong text-center text-sm text-subtle">
                <div>
                    <x-icon name="chart" class="mx-auto mb-2 size-7" />
                    Les statistiques s'afficheront ici une fois le module Demandes en place.
                </div>
            </div>
        </div>

        {{-- Dernières demandes (emplacement) --}}
        <div class="card">
            <h2 class="text-sm font-semibold">Dernières demandes</h2>
            <div class="mt-4 grid h-56 place-items-center rounded-lg border border-dashed border-line-strong text-center text-sm text-subtle">
                <div>
                    <x-icon name="inbox" class="mx-auto mb-2 size-7" />
                    Aucune demande pour le moment.
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
