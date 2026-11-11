<?php

use App\Http\Controllers\AttestationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\DemandeStatutController;
use App\Http\Controllers\EcoleController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\JournalAuditController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ParcoursController;
use App\Http\Controllers\PieceFournieController;
use App\Http\Controllers\PieceRequiseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuitusController;
use App\Http\Controllers\QuitusVisaController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\TypeDemandeController;
use App\Http\Controllers\UtilisateurController;
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

    // Pour ajouter un module : déclarer ses routes ici puis son entrée de menu dans config/navigation.php.

    Route::resource('demandes', DemandeController::class);
    Route::put('/demandes/{demande}/statut', DemandeStatutController::class)->name('demandes.statut');
    Route::post('/demandes/{demande}/paiements', [PaiementController::class, 'store'])->name('demandes.paiements.store');
    Route::delete('/paiements/{paiement}', [PaiementController::class, 'destroy'])->name('paiements.destroy');
    Route::put('/pieces-fournies/{pieceFournie}', PieceFournieController::class)->name('pieces-fournies.update');

    Route::resource('etudiants', EtudiantController::class);

    Route::resource('quitus', QuitusController::class)->parameters(['quitus' => 'quitus']);
    Route::put('/quitus-visas/{quitusVisa}', QuitusVisaController::class)->name('quitus-visas.update');

    Route::resource('attestations', AttestationController::class)->except('show');

    Route::middleware('can:administrer')->group(function () {
        Route::resource('types-demande', TypeDemandeController::class)
            ->parameters(['types-demande' => 'typeDemande'])->except('edit');
        Route::post('/types-demande/{typeDemande}/tarifs', [TarifController::class, 'store'])->name('types-demande.tarifs.store');
        Route::delete('/tarifs/{tarif}', [TarifController::class, 'destroy'])->name('tarifs.destroy');
        Route::post('/types-demande/{typeDemande}/pieces', [PieceRequiseController::class, 'store'])->name('types-demande.pieces.store');
        Route::delete('/pieces-requises/{pieceRequise}', [PieceRequiseController::class, 'destroy'])->name('pieces-requises.destroy');

        Route::resource('ecoles', EcoleController::class)->except('show');
        Route::resource('parcours', ParcoursController::class)->parameters(['parcours' => 'parcours'])->except('show');
        Route::resource('utilisateurs', UtilisateurController::class)->parameters(['utilisateurs' => 'utilisateur'])->except('show');
        Route::get('/journal', JournalAuditController::class)->name('journal.index');
    });
});
