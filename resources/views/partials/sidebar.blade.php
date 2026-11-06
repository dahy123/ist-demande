<aside data-sidebar
    class="fixed inset-y-0 left-0 z-40 flex w-60 -translate-x-full flex-col border-r border-line bg-surface transition-transform duration-200 lg:translate-x-0">
    {{-- Logo --}}
    <div class="flex h-14 items-center justify-between px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="grid size-8 place-items-center rounded-lg bg-brand-600 text-xs font-bold text-white">IST</span>
            <span class="text-sm font-semibold">IST Demandes</span>
        </a>
        <button type="button" data-sidebar-close class="rounded-md p-1 text-muted hover:bg-surface-2 lg:hidden" aria-label="Fermer le menu">
            <x-icon name="x" class="size-4" />
        </button>
    </div>

    {{-- Navigation (définie dans config/navigation.php) --}}
    <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-3">
        @foreach (config('navigation') as $section)
            <div>
                <p class="mb-1 px-2.5 text-[11px] font-semibold tracking-wider text-subtle uppercase">{{ $section['title'] }}</p>
                <ul class="space-y-0.5">
                    @foreach ($section['items'] as $item)
                        @php($active = request()->routeIs($item['active'] ?? $item['route']))
                        <li>
                            <a href="{{ route($item['route']) }}" @class([
                                'flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium transition',
                                'bg-brand-600 text-white' => $active,
                                'text-muted hover:bg-surface-2 hover:text-ink' => ! $active,
                            ]) @if ($active) aria-current="page" @endif>
                                <x-icon :name="$item['icon']" class="size-4" />
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    {{-- Déconnexion --}}
    <div class="border-t border-line p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-red-500/10 hover:text-red-500">
                <x-icon name="logout" class="size-4" />
                Déconnexion
            </button>
        </form>
    </div>
</aside>
