<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\DemandeStage;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Vérification admin dans chaque méthode
     */
    private function verifierAdmin()
    {
        if (!auth()->check() || !auth()->user()->estAdmin()) {
            abort(403, 'Accès réservé aux administrateurs');
        }
    }

    /**
     * Dashboard administrateur avec statistiques globales
     */
    public function dashboard()
    {
        $this->verifierAdmin();
        
        // Statistiques générales
        $stats = [
            'total_utilisateurs' => User::count(),
            'total_etudiants' => User::where('role', 'etudiant')->count(),
            'total_entreprises' => User::where('role', 'entreprise')->count(),
            'total_offres' => Offre::count(),
            'total_candidatures' => Candidature::count(),
            'total_demandes' => DemandeStage::count(),
            'total_stages' => Stage::count(),
            'stages_actifs' => Stage::where('statut', 'en_cours')->count(),
        ];

        // Statistiques mensuelles (6 derniers mois)
        $statsMenuelles = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $mois = $date->format('Y-m');
            
            $statsMenuelles[] = [
                'mois' => $date->format('M Y'),
                'utilisateurs' => User::whereYear('created_at', $date->year)
                                    ->whereMonth('created_at', $date->month)
                                    ->count(),
                'offres' => Offre::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count(),
                'candidatures' => Candidature::whereYear('created_at', $date->year)
                                            ->whereMonth('created_at', $date->month)
                                            ->count(),
                'stages' => Stage::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count(),
            ];
        }

        // Top entreprises par nombre d'offres
        $topEntreprises = Entreprise::withCount('offres')
                                  ->orderBy('offres_count', 'desc')
                                  ->take(5)
                                  ->get();

        // Dernières activités
        $dernieresActivites = [
            'utilisateurs' => User::latest()->take(5)->get(),
            'offres' => Offre::with('entreprise')->latest()->take(5)->get(),
            'candidatures' => Candidature::with(['user', 'offre'])->latest()->take(5)->get(),
            'stages' => Stage::with(['etudiant', 'entreprise'])->latest()->take(5)->get(),
        ];

        // Statistiques par secteur
        $statsSecteurs = Entreprise::select('secteur', DB::raw('count(*) as total'))
                                 ->whereNotNull('secteur')
                                 ->groupBy('secteur')
                                 ->orderBy('total', 'desc')
                                 ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'statsMenuelles', 
            'topEntreprises', 
            'dernieresActivites',
            'statsSecteurs'
        ));
    }

    /**
     * Gestion des utilisateurs
     */
    public function utilisateurs(Request $request)
    {
        $this->verifierAdmin();
        
        $query = User::query();

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $utilisateurs = $query->with('entreprise')
                            ->latest()
                            ->paginate(20);

        $stats = [
            'total' => User::count(),
            'etudiants' => User::where('role', 'etudiant')->count(),
            'entreprises' => User::where('role', 'entreprise')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.utilisateurs.index', compact('utilisateurs', 'stats'));
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function creerUtilisateur()
    {
        return view('admin.utilisateurs.create');
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function stockerUtilisateur(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:etudiant,entreprise,admin',
            'prenom' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:255',
            'filiere' => 'nullable|string|max:255',
            'ecole' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('admin.utilisateurs')
                        ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Modifier un utilisateur
     */
    public function modifierUtilisateur(User $user)
    {
        return view('admin.utilisateurs.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function mettreAJourUtilisateur(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:etudiant,entreprise,admin',
            'prenom' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:255',
            'filiere' => 'nullable|string|max:255',
            'ecole' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.utilisateurs')
                        ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Supprimer un utilisateur
     */
    public function supprimerUtilisateur(User $user)
    {
        // Empêcher la suppression du dernier admin
        if ($user->estAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Impossible de supprimer le dernier administrateur');
        }

        $user->delete();

        return redirect()->route('admin.utilisateurs')
                        ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Gestion des offres
     */
    public function offres(Request $request)
    {
        $query = Offre::with('entreprise');

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type_stage')) {
            $query->where('type_stage', $request->type_stage);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('entreprise', function($eq) use ($search) {
                      $eq->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        $offres = $query->latest()->paginate(20);

        $stats = [
            'total' => Offre::count(),
            'actives' => Offre::where('statut', 'active')->count(),
            'inactives' => Offre::where('statut', 'inactive')->count(),
            'avec_candidatures' => Offre::has('candidatures')->count(),
        ];

        return view('admin.offres.index', compact('offres', 'stats'));
    }

    /**
     * Gestion des stages
     */
    public function stages(Request $request)
    {
        $this->verifierAdmin();
        
        $query = Stage::with(['etudiant', 'entreprise']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhereHas('etudiant', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('entreprise', function($eq) use ($search) {
                      $eq->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        $stages = $query->latest()->paginate(20);

        $stats = [
            'total' => Stage::count(),
            'en_attente' => Stage::where('statut', 'en_attente_debut')->count(),
            'en_cours' => Stage::where('statut', 'en_cours')->count(),
            'termines' => Stage::where('statut', 'termine')->count(),
            'annules' => Stage::where('statut', 'annule')->count(),
        ];

        return view('admin.stages.index', compact('stages', 'stats'));
    }

    /**
     * Statistiques avancées
     */
    public function statistiques()
    {
        // Statistiques par période
        $statsAnnuelles = [];
        for ($i = 2; $i >= 0; $i--) {
            $annee = Carbon::now()->subYears($i)->year;
            $statsAnnuelles[$annee] = [
                'utilisateurs' => User::whereYear('created_at', $annee)->count(),
                'offres' => Offre::whereYear('created_at', $annee)->count(),
                'candidatures' => Candidature::whereYear('created_at', $annee)->count(),
                'stages' => Stage::whereYear('created_at', $annee)->count(),
            ];
        }

        // Taux de conversion
        $totalOffres = Offre::count();
        $totalCandidatures = Candidature::count();
        $candidaturesAcceptees = Candidature::where('statut', 'acceptée')->count();
        $totalStages = Stage::count();

        $tauxConversion = [
            'candidatures_par_offre' => $totalOffres > 0 ? round($totalCandidatures / $totalOffres, 2) : 0,
            'taux_acceptation' => $totalCandidatures > 0 ? round(($candidaturesAcceptees / $totalCandidatures) * 100, 2) : 0,
            'stages_realises' => $candidaturesAcceptees > 0 ? round(($totalStages / $candidaturesAcceptees) * 100, 2) : 0,
        ];

        // Répartition géographique (basée sur les adresses des entreprises)
        $repartitionGeo = Entreprise::select('adresse', DB::raw('count(*) as total'))
                                  ->whereNotNull('adresse')
                                  ->groupBy('adresse')
                                  ->orderBy('total', 'desc')
                                  ->take(10)
                                  ->get();

        return view('admin.statistiques', compact(
            'statsAnnuelles',
            'tauxConversion',
            'repartitionGeo'
        ));
    }

    /**
     * Configuration système
     */
    public function configuration()
    {
        return view('admin.configuration');
    }

    /**
     * Exporter les données
     */
    public function exporterDonnees(Request $request)
    {
        $type = $request->get('type', 'utilisateurs');
        
        switch ($type) {
            case 'utilisateurs':
                $data = User::all();
                break;
            case 'offres':
                $data = Offre::with('entreprise')->get();
                break;
            case 'stages':
                $data = Stage::with(['user', 'entreprise'])->get();
                break;
            default:
                $data = collect();
        }

        $filename = $type . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
