<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/parametres/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/parametres/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/parametres/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Ajouter ici les routes des prochains modules (demandes, étudiants, paiements…)
    // puis déclarer l'entrée de menu dans config/navigation.php.
});
