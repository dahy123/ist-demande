<button type="button" data-theme-toggle {{ $attributes->merge(['class' => 'icon-btn']) }} aria-label="Changer de thème" title="Changer de thème">
    <x-icon name="moon" class="size-4 dark:hidden" />
    <x-icon name="sun" class="hidden size-4 dark:block" />
</button>
