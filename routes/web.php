<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\DemandeStageController;
use App\Http\Controllers\CandidatureController;

// Page d'accueil (publique)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes d'authentification
require __DIR__.'/auth.php';

// Routes protégées
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirection dashboard selon le rôle
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'etudiant' => redirect()->route('etudiant.dashboard'),
            'entreprise' => redirect()->route('entreprise.dashboard'),
            default => abort(403, 'Rôle non reconnu'),
        };
    })->name('dashboard');

    // Gestion du profil utilisateur (commun)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes ÉTUDIANT
    |--------------------------------------------------------------------------
    */
    Route::middleware(['checkrole:etudiant'])->group(function () {
        // Dashboard
        Route::get('/etudiant/dashboard', [EtudiantController::class, 'dashboard'])->name('etudiant.dashboard');

        // Demandes de stage académique
        Route::prefix('demandes')->group(function () {
            Route::get('/create', [DemandeStageController::class, 'create'])->name('demandes.create');
            Route::post('/store', [DemandeStageController::class, 'store'])->name('demandes.store');
        });

        Route::get('/demande-stage/choix', [DemandeStageController::class, 'choixType'])->name('demande.stage.choix');
        Route::get('/demande-stage/form', [DemandeStageController::class, 'form'])->name('demande.stage.form');


        // Candidatures envoyées
        Route::prefix('candidatures')->group(function () {
            Route::get('/mes-candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
            Route::post('/store', [CandidatureController::class, 'store'])->name('candidatures.store');
            Route::delete('/{candidature}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');
        });

        // Route::get('/etudiant/documents', [DocumentController::class, 'index'])->name('etudiant.documents');
        // Route::post('/etudiant/documents', [DocumentController::class, 'store'])->name('etudiant.documents.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes ENTREPRISE
    |--------------------------------------------------------------------------
    */
    Route::middleware(['checkrole:entreprise'])->group(function () {
        // Dashboard
        Route::get('/entreprise/dashboard', [EntrepriseController::class, 'dashboard'])->name('entreprise.dashboard');

        // Profil entreprise
        Route::get('/entreprise/create', [EntrepriseController::class, 'create'])->name('entreprise.create');
        Route::post('/entreprise/store', [EntrepriseController::class, 'store'])->name('entreprise.store');
        Route::get('/entreprise/{entreprise}/edit', [EntrepriseController::class, 'edit'])->name('entreprise.edit');
        Route::put('/entreprise/{entreprise}', [EntrepriseController::class, 'update'])->name('entreprise.update');

        // Gestion des offres
        Route::prefix('entreprise/offres')->group(function () {
            Route::get('/create', [OffreController::class, 'create'])->name('entreprise.offres.create');
            Route::post('/store', [OffreController::class, 'store'])->name('entreprise.offres.store');
            Route::get('/{offre}/edit', [OffreController::class, 'edit'])->name('entreprise.offres.edit');
            Route::put('/{offre}', [OffreController::class, 'update'])->name('entreprise.offres.update');
            Route::delete('/{offre}', [OffreController::class, 'destroy'])->name('entreprise.offres.destroy');
        });

        // Candidatures reçues
        Route::get('/entreprise/candidatures', [EntrepriseController::class, 'candidatures'])->name('entreprise.candidatures');
        Route::patch('/entreprise/candidatures/{candidature}', [EntrepriseController::class, 'updateCandidature'])->name('entreprise.candidatures.update');

        // Actions sur les candidatures
        Route::patch('/entreprise/candidatures/{candidature}/approve', [EntrepriseController::class, 'approveCandidature'])->name('entreprise.candidatures.approve');
        Route::patch('/entreprise/candidatures/{candidature}/reject', [EntrepriseController::class, 'rejectCandidature'])->name('entreprise.candidatures.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes COMMUNES
    |--------------------------------------------------------------------------
    */
    Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
    Route::get('/offres/{offre}', [OffreController::class, 'show'])->name('offres.show');
    Route::get('/entreprises', [EntrepriseController::class, 'index'])->name('entreprise.index');
    Route::get('/entreprises/{entreprise}', [EntrepriseController::class, 'show'])->name('entreprise.show');
    
    // Routes candidatures (communes mais avec vérifications dans le contrôleur)
    Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::get('/candidatures/{candidature}/download/{type}', [CandidatureController::class, 'downloadFile'])->name('candidatures.download');
});
