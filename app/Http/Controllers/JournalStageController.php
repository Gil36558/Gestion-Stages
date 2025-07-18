<?php

namespace App\Http\Controllers;

use App\Models\JournalStage;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JournalStageController extends Controller
{
    /**
     * Affiche le journal de stage pour un étudiant
     */
    public function index(Stage $stage)
    {
        // Vérifier que l'étudiant peut accéder à ce stage
        if (Auth::user()->role === 'etudiant' && $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que l'entreprise peut accéder à ce stage
        if (Auth::user()->role === 'entreprise' && $stage->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        $stage->load(['entreprise', 'etudiant']);
        
        // Récupérer les entrées du journal avec pagination
        $entrees = $stage->journalEntrees()
                        ->with(['commentateur'])
                        ->paginate(10);

        // Statistiques du journal
        $stats = $stage->statistiques_journal;

        return view('journal.index', compact('stage', 'entrees', 'stats'));
    }

    /**
     * Affiche le formulaire pour créer une nouvelle entrée
     */
    public function create(Stage $stage)
    {
        // Seuls les étudiants peuvent créer des entrées
        if (Auth::user()->role !== 'etudiant' || $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que le stage est en cours
        if ($stage->statut !== 'en_cours') {
            return redirect()->route('journal.index', $stage)
                           ->with('error', 'Vous ne pouvez ajouter des entrées que pour un stage en cours.');
        }

        $today = Carbon::today();
        
        // Vérifier s'il existe déjà une entrée pour aujourd'hui
        $entreeExistante = JournalStage::where('stage_id', $stage->id)
                                      ->where('date_activite', $today)
                                      ->first();

        if ($entreeExistante) {
            return redirect()->route('journal.edit', [$stage, $entreeExistante])
                           ->with('info', 'Une entrée existe déjà pour aujourd\'hui. Vous pouvez la modifier.');
        }

        return view('journal.create', compact('stage'));
    }

    /**
     * Enregistre une nouvelle entrée de journal
     */
    public function store(Request $request, Stage $stage)
    {
        // Seuls les étudiants peuvent créer des entrées
        if (Auth::user()->role !== 'etudiant' || $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'date_activite' => 'required|date|before_or_equal:today|after_or_equal:' . $stage->date_debut->format('Y-m-d'),
            'taches_effectuees' => 'required|string|min:10',
            'observations' => 'nullable|string',
            'difficultes_rencontrees' => 'nullable|string',
            'apprentissages' => 'nullable|string',
            'heures_travaillees' => 'nullable|integer|min:1|max:12',
            'fichiers.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB par fichier
            'statut' => 'required|in:brouillon,soumis',
        ]);

        // Vérifier qu'il n'existe pas déjà une entrée pour cette date
        $entreeExistante = JournalStage::where('stage_id', $stage->id)
                                      ->where('date_activite', $validated['date_activite'])
                                      ->exists();

        if ($entreeExistante) {
            return back()->withErrors(['date_activite' => 'Une entrée existe déjà pour cette date.']);
        }

        // Gérer l'upload des fichiers
        $fichiers = [];
        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $fichier) {
                $path = $fichier->store('journal/' . $stage->id, 'public');
                $fichiers[] = [
                    'nom_original' => $fichier->getClientOriginalName(),
                    'chemin' => $path,
                    'taille' => $fichier->getSize(),
                    'type' => $fichier->getMimeType(),
                ];
            }
        }

        $entree = JournalStage::create([
            'stage_id' => $stage->id,
            'user_id' => Auth::id(),
            'date_activite' => $validated['date_activite'],
            'taches_effectuees' => $validated['taches_effectuees'],
            'observations' => $validated['observations'],
            'difficultes_rencontrees' => $validated['difficultes_rencontrees'],
            'apprentissages' => $validated['apprentissages'],
            'heures_travaillees' => $validated['heures_travaillees'],
            'fichiers_joints' => $fichiers,
            'statut' => $validated['statut'],
        ]);

        $message = $validated['statut'] === 'soumis' 
                  ? 'Entrée de journal soumise avec succès !' 
                  : 'Entrée de journal sauvegardée en brouillon.';

        return redirect()->route('journal.index', $stage)->with('success', $message);
    }

    /**
     * Affiche une entrée de journal
     */
    public function show(Stage $stage, JournalStage $journal)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'etudiant' && $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        if (Auth::user()->role === 'entreprise' && $stage->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        $journal->load(['commentateur']);

        return view('journal.show', compact('stage', 'journal'));
    }

    /**
     * Affiche le formulaire d'édition d'une entrée
     */
    public function edit(Stage $stage, JournalStage $journal)
    {
        // Seuls les étudiants peuvent modifier leurs entrées
        if (Auth::user()->role !== 'etudiant' || $journal->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que l'entrée peut être modifiée
        if (!$journal->peutEtreModifiee()) {
            return redirect()->route('journal.show', [$stage, $journal])
                           ->with('error', 'Cette entrée ne peut plus être modifiée.');
        }

        return view('journal.edit', compact('stage', 'journal'));
    }

    /**
     * Met à jour une entrée de journal
     */
    public function update(Request $request, Stage $stage, JournalStage $journal)
    {
        // Seuls les étudiants peuvent modifier leurs entrées
        if (Auth::user()->role !== 'etudiant' || $journal->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que l'entrée peut être modifiée
        if (!$journal->peutEtreModifiee()) {
            return back()->with('error', 'Cette entrée ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'taches_effectuees' => 'required|string|min:10',
            'observations' => 'nullable|string',
            'difficultes_rencontrees' => 'nullable|string',
            'apprentissages' => 'nullable|string',
            'heures_travaillees' => 'nullable|integer|min:1|max:12',
            'fichiers.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'statut' => 'required|in:brouillon,soumis',
        ]);

        // Gérer l'upload des nouveaux fichiers
        $fichiers = $journal->fichiers_joints ?? [];
        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $fichier) {
                $path = $fichier->store('journal/' . $stage->id, 'public');
                $fichiers[] = [
                    'nom_original' => $fichier->getClientOriginalName(),
                    'chemin' => $path,
                    'taille' => $fichier->getSize(),
                    'type' => $fichier->getMimeType(),
                ];
            }
        }

        $journal->update([
            'taches_effectuees' => $validated['taches_effectuees'],
            'observations' => $validated['observations'],
            'difficultes_rencontrees' => $validated['difficultes_rencontrees'],
            'apprentissages' => $validated['apprentissages'],
            'heures_travaillees' => $validated['heures_travaillees'],
            'fichiers_joints' => $fichiers,
            'statut' => $validated['statut'],
        ]);

        $message = $validated['statut'] === 'soumis' 
                  ? 'Entrée de journal soumise avec succès !' 
                  : 'Entrée de journal mise à jour.';

        return redirect()->route('journal.show', [$stage, $journal])->with('success', $message);
    }

    /**
     * Supprime une entrée de journal
     */
    public function destroy(Stage $stage, JournalStage $journal)
    {
        // Seuls les étudiants peuvent supprimer leurs entrées en brouillon
        if (Auth::user()->role !== 'etudiant' || $journal->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($journal->statut !== 'brouillon') {
            return back()->with('error', 'Seules les entrées en brouillon peuvent être supprimées.');
        }

        // Supprimer les fichiers associés
        if ($journal->fichiers_joints) {
            foreach ($journal->fichiers_joints as $fichier) {
                Storage::disk('public')->delete($fichier['chemin']);
            }
        }

        $journal->delete();

        return redirect()->route('journal.index', $stage)->with('success', 'Entrée supprimée avec succès.');
    }

    /**
     * Soumet une entrée en brouillon
     */
    public function soumettre(Stage $stage, JournalStage $journal)
    {
        // Seuls les étudiants peuvent soumettre leurs entrées
        if (Auth::user()->role !== 'etudiant' || $journal->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if (!$journal->peutEtreSoumise()) {
            return back()->with('error', 'Cette entrée ne peut pas être soumise.');
        }

        $journal->update(['statut' => 'soumis']);

        return back()->with('success', 'Entrée soumise avec succès !');
    }

    /**
     * Ajoute un commentaire d'entreprise
     */
    public function commenter(Request $request, Stage $stage, JournalStage $journal)
    {
        // Seules les entreprises peuvent commenter
        if (Auth::user()->role !== 'entreprise' || $stage->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        if (!$journal->peutEtreCommentee()) {
            return back()->with('error', 'Cette entrée ne peut pas être commentée.');
        }

        $validated = $request->validate([
            'commentaire_entreprise' => 'required|string|min:5',
            'note_journee' => 'nullable|integer|min:1|max:5',
            'statut' => 'required|in:valide,rejete',
        ]);

        $journal->update([
            'commentaire_entreprise' => $validated['commentaire_entreprise'],
            'note_journee' => $validated['note_journee'],
            'date_commentaire_entreprise' => now(),
            'commentaire_par' => Auth::id(),
            'statut' => $validated['statut'],
        ]);

        $message = $validated['statut'] === 'valide' 
                  ? 'Entrée validée avec succès !' 
                  : 'Entrée rejetée avec commentaires.';

        return back()->with('success', $message);
    }

    /**
     * Télécharge un fichier joint
     */
    public function telechargerFichier(Stage $stage, JournalStage $journal, $index)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'etudiant' && $journal->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        if (Auth::user()->role === 'entreprise' && $stage->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        $fichiers = $journal->fichiers_joints ?? [];
        
        if (!isset($fichiers[$index])) {
            abort(404, 'Fichier non trouvé');
        }

        $fichier = $fichiers[$index];
        
        if (!Storage::disk('public')->exists($fichier['chemin'])) {
            abort(404, 'Fichier non trouvé sur le serveur');
        }

        return response()->download(
            Storage::disk('public')->path($fichier['chemin']), 
            $fichier['nom_original']
        );
    }

    /**
     * Vue calendrier pour l'entreprise
     */
    public function calendrier(Stage $stage)
    {
        // Seules les entreprises peuvent voir le calendrier
        if (Auth::user()->role !== 'entreprise' || $stage->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        $stage->load(['etudiant']);
        
        // Récupérer toutes les entrées pour le calendrier
        $entrees = $stage->journalEntrees()
                        ->get()
                        ->keyBy(function($entree) {
                            return $entree->date_activite->format('Y-m-d');
                        });

        return view('journal.calendrier', compact('stage', 'entrees'));
    }
}
