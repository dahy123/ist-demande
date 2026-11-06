// Sidebar mobile
const sidebar = document.querySelector('[data-sidebar]');
const overlay = document.querySelector('[data-sidebar-overlay]');

function toggleSidebar(open) {
    if (!sidebar) return;
    sidebar.classList.toggle('-translate-x-full', !open);
    overlay?.classList.toggle('hidden', !open);
}

document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => toggleSidebar(true));
document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => toggleSidebar(false));
overlay?.addEventListener('click', () => toggleSidebar(false));

// Thème clair / sombre
document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const dark = document.documentElement.classList.toggle('dark');
        try {
            localStorage.setItem('theme', dark ? 'dark' : 'light');
        } catch (e) {}
    });
});

// Menus déroulants
document.addEventListener('click', (event) => {
    document.querySelectorAll('[data-dropdown]').forEach((dropdown) => {
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        const clickedToggle = event.target.closest('[data-dropdown-toggle]');

        if (clickedToggle && dropdown.contains(clickedToggle)) {
            menu.classList.toggle('hidden');
        } else if (!dropdown.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    toggleSidebar(false);
    document.querySelectorAll('[data-dropdown-menu]').forEach((menu) => menu.classList.add('hidden'));
});
