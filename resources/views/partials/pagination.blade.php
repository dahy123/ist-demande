@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-between gap-3 text-sm">
        <p class="text-muted">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} sur {{ $paginator->total() }}
        </p>

        <ul class="flex flex-wrap items-center gap-1">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="grid h-8 min-w-8 place-items-center rounded-md px-2 text-subtle">‹</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="grid h-8 min-w-8 place-items-center rounded-md px-2 text-muted hover:bg-surface-2 hover:text-ink" aria-label="Page précédente">‹</a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="grid h-8 min-w-8 place-items-center px-2 text-subtle">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="grid h-8 min-w-8 place-items-center rounded-md bg-brand-600 px-2 font-medium text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="grid h-8 min-w-8 place-items-center rounded-md px-2 text-muted hover:bg-surface-2 hover:text-ink">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="grid h-8 min-w-8 place-items-center rounded-md px-2 text-muted hover:bg-surface-2 hover:text-ink" aria-label="Page suivante">›</a>
                @else
                    <span class="grid h-8 min-w-8 place-items-center rounded-md px-2 text-subtle">›</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
