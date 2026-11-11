<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paramétrage (écoles, types de demande, utilisateurs, journal) : réservé aux administrateurs.
        Gate::define('administrer', fn (User $user): bool => $user->isAdmin());

        Paginator::defaultView('partials.pagination');
    }
}
