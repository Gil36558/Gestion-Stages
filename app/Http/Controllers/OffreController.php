<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OffreController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste publique des offres
     */
    public function index()
    {
        $offres = Offre::with('entreprise')
                      ->where('date_fin', '>=', now())
                      ->orWhereNull('date_fin')
                      ->latest()
                      ->paginate(12);
        
        return view('offres.index', compact('offres'));
    }

    /**
     * Affiche une offre spécifique
     */
    public function show(Offre $offre)
    {
        $offre->load('entreprise');
        return view('offres.show', compact('offre'));
    }

    /**
     * Formulaire de création d'offre (entreprises uniquement)
     */
    public function create()
    {
        if (Auth::user()->role !== 'entreprise') {
            abort(403, 'Accès réservé aux entreprises');
        }

        $entreprise = Auth::user()->entreprise;
        if (!$entreprise) {
            return redirect()->route('entreprise.create')
                           ->with('error', 'Veuillez d\'abord créer votre profil entreprise');
        }

        return view('offres.create', compact('entreprise'));
    }

    /**
     * Enregistre une nouvelle offre
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'entreprise') {
            abort(403, 'Accès réservé aux entreprises');
        }

        $entreprise = Auth::user()->entreprise;
        if (!$entreprise) {
            return redirect()->route('entreprise.create')
                           ->with('error', 'Veuillez d\'abord créer votre profil entreprise');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'competences_requises' => 'nullable|string',
            'type_stage' => 'required|in:academique,professionnel,les_deux',
            'duree' => 'nullable|string|max:100',
            'remuneration' => 'nullable|string|max:100',
            'lieu' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'date_limite_candidature' => 'nullable|date|after_or_equal:today',
            'nombre_postes' => 'nullable|integer|min:1|max:50',
            'niveau_etudes' => 'nullable|string|max:100',
            'statut' => 'nullable|in:active,inactive',
        ]);

        $validated['entreprise_id'] = $entreprise->id;
        $validated['statut'] = $validated['statut'] ?? 'active';

        $offre = Offre::create($validated);

        return redirect()->route('entreprise.dashboard')
                        ->with('success', 'Offre créée avec succès !');
    }

    /**
     * Formulaire d'édition d'offre
     */
    public function edit(Offre $offre)
    {
        // Vérification simple : l'utilisateur doit être propriétaire de l'entreprise
        if (Auth::user()->role !== 'entreprise' || Auth::user()->entreprise->id !== $offre->entreprise_id) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('offres.edit', compact('offre'));
    }

    /**
     * Met à jour une offre
     */
    public function update(Request $request, Offre $offre)
    {
        // Vérification simple : l'utilisateur doit être propriétaire de l'entreprise
        if (Auth::user()->role !== 'entreprise' || Auth::user()->entreprise->id !== $offre->entreprise_id) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'competences_requises' => 'nullable|string',
            'type_stage' => 'required|in:academique,professionnel,les_deux',
            'duree' => 'nullable|string|max:100',
            'remuneration' => 'nullable|string|max:100',
            'lieu' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'date_limite_candidature' => 'nullable|date|after_or_equal:today',
            'nombre_postes' => 'nullable|integer|min:1|max:50',
            'niveau_etudes' => 'nullable|string|max:100',
            'statut' => 'nullable|in:active,inactive',
        ]);

        $offre->update($validated);

        return redirect()->route('entreprise.dashboard')
                        ->with('success', 'Offre mise à jour avec succès !');
    }

    /**
     * Supprime une offre
     */
    public function destroy(Offre $offre)
    {
        // Vérification simple : l'utilisateur doit être propriétaire de l'entreprise
        if (Auth::user()->role !== 'entreprise' || Auth::user()->entreprise->id !== $offre->entreprise_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $offre->delete();

        return redirect()->route('entreprise.dashboard')
                        ->with('success', 'Offre supprimée avec succès');
    }
}
