<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\DemandeStage;
use App\Models\Document;
use App\Models\Offre;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    /**
     * Affiche le tableau de bord de l'étudiant.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Statistiques des candidatures aux offres
        $candidatureStats = [
            'total'      => $user->candidatures()->count(),
            'en_attente' => $user->candidatures()->where('statut', 'en attente')->count(),
            'acceptees'  => $user->candidatures()->where('statut', 'acceptée')->count(),
            'refusees'   => $user->candidatures()->where('statut', 'refusée')->count(),
        ];

        // Statistiques des demandes de stage directes
        $demandeStats = [
            'total'      => $user->demandesStages()->count(),
            'en_attente' => $user->demandesStages()->where('statut', 'en attente')->count(),
            'validees'   => $user->demandesStages()->where('statut', 'validée')->count(),
            'refusees'   => $user->demandesStages()->where('statut', 'refusée')->count(),
        ];

        // Statistiques globales
        $stats = [
            'candidatures' => $candidatureStats,
            'demandes'     => $demandeStats,
            'documents'    => $user->documents()->count(),
        ];

        // Candidatures récentes aux offres
        $recentCandidatures = $user->candidatures()
            ->with(['offre.entreprise'])
            ->latest()
            ->take(5)
            ->get();

        // Demandes de stage récentes
        $recentDemandes = $user->demandesStages()
            ->with('entreprise')
            ->latest()
            ->take(5)
            ->get();

        // Offres disponibles pour candidater
        $offresDisponibles = Offre::with('entreprise')
            ->whereDoesntHave('candidatures', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('etudiant.dashboard', compact(
            'stats', 
            'recentCandidatures', 
            'recentDemandes', 
            'offresDisponibles'
        ));
    }
}
