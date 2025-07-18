<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\DemandeStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Gestion du logo
        if ($request->hasFile('logo')) {
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
        $this->authorize('update', $entreprise);
        return view('entreprises.edit', compact('entreprise'));
    }

    /**
     * Met à jour le profil entreprise
     */
    public function update(Request $request, Entreprise $entreprise)
    {
        $this->authorize('update', $entreprise);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'secteur' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'site_web' => 'nullable|url',
            'taille' => 'required|in:petite,moyenne,grande',
        ]);

        // Gestion du logo
        if ($request->hasFile('logo')) {
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
     * Change le statut d'une candidature
     */
    public function updateCandidature(Request $request, Candidature $candidature)
    {
        $this->authorize('update', $candidature->offre->entreprise);

        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee'
        ]);

        $candidature->update(['statut' => $request->statut]);

        return back()->with('success', 'Statut de la candidature mis à jour');
    }

    /**
     * Approuve une candidature
     */
    public function approveCandidature(Request $request, Candidature $candidature)
    {
        $this->authorize('update', $candidature->offre->entreprise);
        
        $candidature->update(['statut' => 'acceptee']);
        
        return back()->with('success', 'Candidature approuvée avec succès');
    }

    /**
     * Rejette une candidature
     */
    public function rejectCandidature(Request $request, Candidature $candidature)
    {
        $this->authorize('update', $candidature->offre->entreprise);
        
        $validated = $request->validate([
            'motif_refus' => 'nullable|string|max:500'
        ]);
        
        $candidature->update([
            'statut' => 'refusee',
            'motif_refus' => $validated['motif_refus'] ?? null
        ]);
        
        return back()->with('success', 'Candidature rejetée');
    }

    /**
     * Approuve une demande de stage
     */
    public function approveDemandeStage(Request $request, DemandeStage $demande)
    {
        // Vérifier que la demande appartient à l'entreprise de l'utilisateur connecté
        if ($demande->entreprise_id !== auth()->user()->entreprise->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $demande->update(['statut' => 'validée']);
        
        return back()->with('success', 'Demande de stage approuvée avec succès');
    }

    /**
     * Rejette une demande de stage
     */
    public function rejectDemandeStage(Request $request, DemandeStage $demande)
    {
        // Vérifier que la demande appartient à l'entreprise de l'utilisateur connecté
        if ($demande->entreprise_id !== auth()->user()->entreprise->id) {
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

    public function show(Entreprise $entreprise)
    {
        $entreprise->load('offres'); // très important pour éviter les requêtes multiples

        return view('entreprise.show', compact('entreprise'));
    }

}
