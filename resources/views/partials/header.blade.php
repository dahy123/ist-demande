@php($user = auth()->user())

<header class="sticky top-0 z-20 bg-canvas/80 px-4 backdrop-blur py-1.5">
    <div class="flex h-14 items-center gap-3">
        <button type="button" data-sidebar-open class="rounded-md p-1.5 text-muted hover:bg-surface lg:hidden" aria-label="Ouvrir le menu">
            <x-icon name="menu" />
        </button>

        <h1 class="truncate text-lg font-semibold">{{ $title }}</h1>

        <div class="ml-auto flex items-center gap-2">
            {{-- Recherche (à brancher plus tard) --}}
            <label class="relative hidden md:block">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2 text-subtle" />
                <input type="search" placeholder="Rechercher…" class="w-56 rounded-lg border border-line bg-surface py-2 pr-3 pl-8 text-sm outline-none placeholder:text-subtle focus:border-brand-500 focus:ring-3 focus:ring-brand-500/20">
            </label>

            <x-theme-toggle />

            <button type="button" class="icon-btn" aria-label="Notifications">
                <x-icon name="bell" class="size-4" />
            </button>

            {{-- Menu utilisateur --}}
            <div class="relative" data-dropdown>
                <button type="button" data-dropdown-toggle class="flex items-center gap-2 rounded-lg border border-line bg-surface py-1 pr-2 pl-1 hover:bg-surface-2">
                    <span class="grid size-7 place-items-center rounded-md bg-brand-100 text-[11px] font-semibold text-brand-700 dark:bg-brand-500/20 dark:text-brand-300">{{ $user->initiales }}</span>
                    <span class="hidden text-left leading-tight sm:block">
                        <span class="block text-xs font-semibold">{{ $user->nom_complet }}</span>
                        <span class="block text-[11px] text-muted">{{ $user->service ?? $user->identifiant }}</span>
                    </span>
                    <x-icon name="chevron-down" class="size-3.5 text-subtle" />
                </button>

                <div data-dropdown-menu class="absolute right-0 mt-1.5 hidden w-44 rounded-lg border border-line bg-surface p-1 shadow-lg">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-md px-2.5 py-1.5 text-sm hover:bg-surface-2">
                        <x-icon name="user" class="size-4" /> Mon profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-md px-2.5 py-1.5 text-sm text-red-500 hover:bg-red-500/10">
                            <x-icon name="logout" class="size-4" /> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
