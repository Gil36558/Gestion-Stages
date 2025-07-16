<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\DemandeStage;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    /**
     * Affiche le tableau de bord de l'étudiant.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Statistiques globales
        $stats = [
            'envoyees'    => $user->candidatures()->count(),
            'en_attente'  => $user->candidatures()->where('statut', 'en attente')->count(),
            'acceptees'   => $user->candidatures()->where('statut', 'acceptée')->count(),
            'documents'   => $user->documents()->count(),
        ];

        // Candidatures récentes (avec relations vers offre et entreprise)
        $recentCandidatures = $user->candidatures()
            ->with(['offre.entreprise'])
            ->latest()
            ->take(5)
            ->get();

        // Demandes de stage récentes (si la relation existe)
        $recentDemandes = $user->demandesStages()
            ->with('entreprise') // si tu as une relation entreprise dans DemandeStage
            ->latest()
            ->take(5)
            ->get();

        return view('etudiant.dashboard', compact('stats', 'recentCandidatures', 'recentDemandes'));
    }
}
