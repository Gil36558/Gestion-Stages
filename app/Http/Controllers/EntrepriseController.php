<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\DemandeStage;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EntrepriseController extends Controller
{
    /**
     * Affiche la liste des entreprises
     */
    public function index()
    {
        $entreprises = Entreprise::with('user')->latest()->paginate(10);
        return view('entreprise.index', compact('entreprises'));
    }

    /**
     * Affiche le formulaire de création d'entreprise
     */
    public function create()
    {
        return view('entreprise.create');
    }

    /**
     * Affiche le dashboard entreprise
     */
    public function dashboard()
    {
        if (!auth()->user()->estEntreprise()) {
            abort(403, 'Accès réservé aux entreprises');
        }

        $entreprise = auth()->user()->entreprise()->with(['offres', 'candidatures.etudiant'])->first();

        if (!$entreprise) {
            return view('entreprise.dashboard', [
                'entreprise' => null,
                'stats' => [
                    'offres' => 0,
                    'candidatures' => 0,
                    'candidatures_recentes' => collect(),
                ],
                'profilIncomplet' => true,
            ]);
        }

        $profilIncomplet = !$entreprise->secteur 
                        || !$entreprise->description
                        || !$entreprise->site_web
                        || !$entreprise->ville;

        $stats = [
            'offres' => $entreprise->offres->count(),
            'candidatures' => $entreprise->candidatures->count(),
            'candidatures_recentes' => $entreprise->candidatures()
                                                ->with('etudiant')
                                                ->latest()
                                                ->take(5)
                                                ->get()
        ];

        return view('entreprise.dashboard', compact('entreprise', 'stats', 'profilIncomplet'));
    }

    /**
     * Enregistre une nouvelle entreprise
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'secteur' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'site_web' => 'nullable|url',
            'taille' => 'required|in:petite,moyenne,grande',
            'ville' => 'required|string|max:255',
        ]);

        // Gestion du logo avec vérification
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $validated['logo'] = $request->file('logo')->store('logos/entreprises', 'public');
        }

        // Création de l'entreprise liée à l'utilisateur
        $entreprise = auth()->user()->entreprise()->create($validated);

        return redirect()->route('entreprise.dashboard')
                        ->with('success', 'Profil entreprise créé avec succès');
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Entreprise $entreprise)
    {
        // Vérifier que l'utilisateur peut modifier cette entreprise
        if ($entreprise->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        return view('entreprise.edit', compact('entreprise'));
    }

    /**
     * Met à jour le profil entreprise
     */
    public function update(Request $request, Entreprise $entreprise)
    {
        // Vérifier que l'utilisateur peut modifier cette entreprise
        if ($entreprise->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'secteur' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'site_web' => 'nullable|url',
            'taille' => 'required|in:petite,moyenne,grande',
        ]);

        // Gestion du logo avec vérification
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Supprime l'ancien logo si existant
            if ($entreprise->logo) {
                Storage::disk('public')->delete($entreprise->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos/entreprises', 'public');
        }

        $entreprise->update($validated);

        return redirect()->route('entreprise.dashboard')
                        ->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Gestion des candidatures et demandes reçues
     */
    public function candidatures()
    {
        $entreprise = auth()->user()->entreprise;
        
        // Candidatures aux offres
        $candidatures = $entreprise->candidatures()
                                ->with(['offre', 'user'])
                                ->latest()
                                ->paginate(5, ['*'], 'candidatures');

        // Demandes de stage directes
        $demandesStages = $entreprise->demandesStages()
                                   ->with(['etudiants'])
                                   ->latest()
                                   ->paginate(5, ['*'], 'demandes');

        return view('entreprise.candidatures', compact('candidatures', 'demandesStages'));
    }

    /**
     * Vue unifiée des demandes (candidatures + demandes directes)
     */
    public function demandes()
    {
        if (!auth()->user()->estEntreprise()) {
            abort(403, 'Accès réservé aux entreprises');
        }

        $entreprise = auth()->user()->entreprise;
        
        if (!$entreprise) {
            return redirect()->route('entreprise.create')
                           ->with('error', 'Veuillez d\'abord créer votre profil entreprise');
        }

        // Candidatures aux offres (avec relations)
        $candidatures = Candidature::whereHas('offre', function($query) use ($entreprise) {
                                    $query->where('entreprise_id', $entreprise->id);
                                })
                                ->with(['user', 'offre'])
                                ->latest()
                                ->get();

        // Demandes de stage directes
        $demandes = DemandeStage::where('entreprise_id', $entreprise->id)
                              ->with(['etudiants'])
                              ->latest()
                              ->get();

        return view('entreprise.demandes', compact('candidatures', 'demandes'));
    }

    /**
     * Change le statut d'une candidature
     */
    public function updateCandidature(Request $request, Candidature $candidature)
    {
        // Vérifier que la candidature appartient à l'entreprise
        $entreprise = auth()->user()->entreprise;
        if (!$entreprise || $candidature->offre->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee'
        ]);

        $candidature->update(['statut' => $request->statut]);

        return back()->with('success', 'Statut de la candidature mis à jour');
    }

    /**
     * Approuve une candidature et crée automatiquement un stage
     */
    public function approveCandidature(Request $request, Candidature $candidature)
    {
        // Vérifier que la candidature appartient à l'entreprise
        $entreprise = auth()->user()->entreprise;
        if (!$entreprise || $candidature->offre->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier si un stage n'existe pas déjà pour cette candidature
        if ($candidature->stage) {
            return back()->with('error', 'Un stage existe déjà pour cette candidature.');
        }
        
        $candidature->update(['statut' => 'acceptée']);
        
        // Créer automatiquement un stage
        $this->creerStageDepuisCandidature($candidature);
        
        return back()->with('success', 'Candidature approuvée avec succès ! Un stage a été créé automatiquement.');
    }

    /**
     * Rejette une candidature
     */
    public function rejectCandidature(Request $request, Candidature $candidature)
    {
        // Vérifier que la candidature appartient à l'entreprise
        $entreprise = auth()->user()->entreprise;
        if (!$entreprise || $candidature->offre->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'motif_refus' => 'nullable|string|max:500'
        ]);
        
        $candidature->update([
            'statut' => 'refusée',
            'motif_refus' => $validated['motif_refus'] ?? null
        ]);
        
        return back()->with('success', 'Candidature rejetée');
    }

    /**
     * Approuve une demande de stage et crée automatiquement un stage
     */
    public function approveDemandeStage(Request $request, DemandeStage $demande)
    {
        // Vérifier que la demande appartient à l'entreprise de l'utilisateur connecté
        $entreprise = auth()->user()->entreprise;
        if (!$entreprise || $demande->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier si un stage n'existe pas déjà pour cette demande
        if ($demande->stage) {
            return back()->with('error', 'Un stage existe déjà pour cette demande.');
        }
        
        $demande->update(['statut' => 'validée']);
        
        // Créer automatiquement un stage
        $this->creerStageDepuisDemandeStage($demande);
        
        return back()->with('success', 'Demande de stage approuvée avec succès ! Un stage a été créé automatiquement.');
    }

    /**
     * Rejette une demande de stage
     */
    public function rejectDemandeStage(Request $request, DemandeStage $demande)
    {
        // Vérifier que la demande appartient à l'entreprise de l'utilisateur connecté
        $entreprise = auth()->user()->entreprise;
        if (!$entreprise || $demande->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'motif_refus' => 'nullable|string|max:500'
        ]);
        
        $demande->update([
            'statut' => 'refusée',
            'motif_refus' => $validated['motif_refus'] ?? null
        ]);
        
        return back()->with('success', 'Demande de stage rejetée');
    }

    /**
     * Affiche les stages de l'entreprise
     */
    public function stages()
    {
        $entreprise = auth()->user()->entreprise;
        
        if (!$entreprise) {
            return redirect()->route('entreprise.create')
                           ->with('error', 'Veuillez d\'abord créer votre profil entreprise');
        }
        
        $stages = Stage::where('entreprise_id', $entreprise->id)
                     ->with(['etudiant', 'candidature.offre', 'demandeStage'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('entreprise.stages.index', compact('stages'));
    }

    public function show(Entreprise $entreprise)
    {
        $entreprise->load('offres'); // très important pour éviter les requêtes multiples

        return view('entreprise.show', compact('entreprise'));
    }

    /**
     * Crée un stage à partir d'une candidature acceptée
     */
    private function creerStageDepuisCandidature(Candidature $candidature)
    {
        $offre = $candidature->offre;
        
        return Stage::create([
            'user_id' => $candidature->user_id,
            'entreprise_id' => $offre->entreprise_id,
            'candidature_id' => $candidature->id,
            'titre' => $offre->titre,
            'description' => $offre->description,
            'date_debut' => $offre->date_debut,
            'date_fin' => $offre->date_fin ?? Carbon::parse($offre->date_debut)->addMonths(3),
            'lieu' => $offre->lieu,
            'statut' => 'en_attente_debut',
        ]);
    }

    /**
     * Crée un stage à partir d'une demande de stage acceptée
     */
    private function creerStageDepuisDemandeStage(DemandeStage $demande)
    {
        return Stage::create([
            'user_id' => $demande->etudiants->first()->id,
            'entreprise_id' => $demande->entreprise_id,
            'demande_stage_id' => $demande->id,
            'titre' => $demande->objet,
            'description' => $demande->objectifs_stage,
            'date_debut' => $demande->periode_debut,
            'date_fin' => $demande->periode_fin,
            'lieu' => $demande->entreprise->adresse ?? 'À définir',
            'statut' => 'en_attente_debut',
        ]);
    }
}
