@props(['title' => null])

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>
    @include('partials.theme-script')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas font-sans text-ink antialiased">
    {{-- Fond sombre derrière la sidebar sur mobile --}}
    <div data-sidebar-overlay class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

    @include('partials.sidebar')

    <div class="lg:pl-60">
        @include('partials.header', ['title' => $title])

        <main class="px-4 pb-6 lg:px-6">
            @if (session('status'))
                <div class="mb-4 flex items-center gap-2 rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-700 dark:text-emerald-400">
                    <x-icon name="check" class="size-4" />
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
