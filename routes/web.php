<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\DemandeStageController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\JournalStageController;

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
            'admin' => redirect()->route('admin.dashboard'),
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
            Route::post('/verifier-binome', [DemandeStageController::class, 'verifierBinome'])->name('demandes.verifier-binome');
        });

        Route::get('/demande-stage/choix', [DemandeStageController::class, 'choixType'])->name('demande.stage.choix');
        Route::get('/demande-stage/form', [DemandeStageController::class, 'form'])->name('demande.stage.form');

        // Candidatures aux offres (nouveau système)
        Route::prefix('candidatures')->group(function () {
            Route::get('/mes-candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
            Route::patch('/{candidature}/cancel', [CandidatureController::class, 'cancel'])->name('candidatures.cancel');
            Route::delete('/{candidature}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');
        });
        
        // Route candidature store (hors du prefix pour être accessible via POST /candidatures)
        Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');

        // Stages de l'étudiant
        Route::prefix('stages')->group(function () {
            Route::get('/', [StageController::class, 'index'])->name('stages.index');
            Route::get('/{stage}', [StageController::class, 'show'])->name('stages.show');
            Route::post('/{stage}/demarrer', [StageController::class, 'demarrer'])->name('stages.demarrer');
            Route::post('/{stage}/terminer', [StageController::class, 'terminer'])->name('stages.terminer');
            Route::post('/{stage}/auto-evaluer', [StageController::class, 'autoEvaluer'])->name('stages.auto-evaluer');
            Route::get('/{stage}/download/{type}', [StageController::class, 'telechargerDocument'])->name('stages.download');
            
            // Journal de stage (étudiant)
            Route::get('/{stage}/journal', [JournalStageController::class, 'index'])->name('journal.index');
            Route::get('/{stage}/journal/create', [JournalStageController::class, 'create'])->name('journal.create');
            Route::post('/{stage}/journal', [JournalStageController::class, 'store'])->name('journal.store');
            Route::get('/{stage}/journal/{journal}', [JournalStageController::class, 'show'])->name('journal.show');
            Route::get('/{stage}/journal/{journal}/edit', [JournalStageController::class, 'edit'])->name('journal.edit');
            Route::put('/{stage}/journal/{journal}', [JournalStageController::class, 'update'])->name('journal.update');
            Route::delete('/{stage}/journal/{journal}', [JournalStageController::class, 'destroy'])->name('journal.destroy');
            Route::post('/{stage}/journal/{journal}/soumettre', [JournalStageController::class, 'soumettre'])->name('journal.soumettre');
            Route::get('/{stage}/journal/{journal}/fichier/{index}', [JournalStageController::class, 'telechargerFichier'])->name('journal.fichier');
        });
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

        // Candidatures reçues (ancien système - demandes directes)
        Route::get('/entreprise/candidatures', [EntrepriseController::class, 'candidatures'])->name('entreprise.candidatures');
        Route::patch('/entreprise/candidatures/{candidature}', [EntrepriseController::class, 'updateCandidature'])->name('entreprise.candidatures.update');

        // Vue unifiée des demandes (candidatures + demandes directes)
        Route::get('/entreprise/demandes', [EntrepriseController::class, 'demandes'])->name('entreprise.demandes');

        // Actions sur les demandes de stage (ancien système - demandes directes)
        Route::patch('/entreprise/demandes/{demande}/approve', [EntrepriseController::class, 'approveDemandeStage'])->name('entreprise.demandes.approve');
        Route::patch('/entreprise/demandes/{demande}/reject', [EntrepriseController::class, 'rejectDemandeStage'])->name('entreprise.demandes.reject');

        // Actions sur les candidatures aux offres (nouveau système)
        Route::post('/entreprise/candidatures-offres/{candidature}/approve', [CandidatureController::class, 'approve'])->name('entreprise.candidatures.offres.approve');
        Route::post('/entreprise/candidatures-offres/{candidature}/reject', [CandidatureController::class, 'reject'])->name('entreprise.candidatures.offres.reject');

        // Stages de l'entreprise
        Route::prefix('entreprise/stages')->group(function () {
            Route::get('/', [StageController::class, 'indexEntreprise'])->name('entreprise.stages.index');
            Route::post('/{stage}/evaluer', [StageController::class, 'evaluer'])->name('stages.evaluer');
            Route::post('/{stage}/annuler', [StageController::class, 'annuler'])->name('stages.annuler');
            
            // Journal de stage (entreprise)
            Route::get('/{stage}/journal', [JournalStageController::class, 'index'])->name('entreprise.journal.index');
            Route::get('/{stage}/journal/{journal}', [JournalStageController::class, 'show'])->name('entreprise.journal.show');
            Route::post('/{stage}/journal/{journal}/commenter', [JournalStageController::class, 'commenter'])->name('entreprise.journal.commenter');
            Route::get('/{stage}/journal/{journal}/fichier/{index}', [JournalStageController::class, 'telechargerFichier'])->name('entreprise.journal.fichier');
            Route::get('/{stage}/calendrier', [JournalStageController::class, 'calendrier'])->name('entreprise.journal.calendrier');
        });
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
    
    // Routes candidatures aux offres (communes mais avec vérifications dans le contrôleur)
    Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::get('/candidatures/{candidature}/download/{type}', [CandidatureController::class, 'downloadFile'])->name('candidatures.download');
    
    // Routes demandes de stage
    Route::get('/demandes/{demande}', [DemandeStageController::class, 'show'])->name('demandes.show');

    /*
    |--------------------------------------------------------------------------
    | Routes ADMINISTRATEUR
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        // Vérification du rôle admin dans le contrôleur
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        
        // Gestion des utilisateurs
        Route::get('/utilisateurs', [App\Http\Controllers\AdminController::class, 'utilisateurs'])->name('utilisateurs');
        Route::get('/utilisateurs/create', [App\Http\Controllers\AdminController::class, 'creerUtilisateur'])->name('utilisateurs.create');
        Route::post('/utilisateurs', [App\Http\Controllers\AdminController::class, 'stockerUtilisateur'])->name('utilisateurs.store');
        Route::get('/utilisateurs/{user}/edit', [App\Http\Controllers\AdminController::class, 'modifierUtilisateur'])->name('utilisateurs.edit');
        Route::put('/utilisateurs/{user}', [App\Http\Controllers\AdminController::class, 'mettreAJourUtilisateur'])->name('utilisateurs.update');
        Route::delete('/utilisateurs/{user}', [App\Http\Controllers\AdminController::class, 'supprimerUtilisateur'])->name('utilisateurs.destroy');
        
        // Gestion des offres
        Route::get('/offres', [App\Http\Controllers\AdminController::class, 'offres'])->name('offres');
        
        // Gestion des stages
        Route::get('/stages', [App\Http\Controllers\AdminController::class, 'stages'])->name('stages');
        
        // Statistiques avancées
        Route::get('/statistiques', [App\Http\Controllers\AdminController::class, 'statistiques'])->name('statistiques');
        
        // Configuration
        Route::get('/configuration', [App\Http\Controllers\AdminController::class, 'configuration'])->name('configuration');
        
        // Export de données
        Route::get('/export', [App\Http\Controllers\AdminController::class, 'exporterDonnees'])->name('export');
    });
});
