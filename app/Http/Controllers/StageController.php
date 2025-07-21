<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Candidature;
use App\Models\DemandeStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StageController extends Controller
{
    /**
     * Affiche la liste des stages pour l'étudiant connecté
     */
    public function index()
    {
        $user = Auth::user();
        
        $stages = Stage::where('user_id', $user->id)
            ->with(['entreprise', 'candidature.offre', 'demandeStage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('stages.index', compact('stages'));
    }

    /**
     * Affiche les détails d'un stage
     */
    public function show(Stage $stage)
    {
        // Vérifier que l'utilisateur peut voir ce stage
        if (Auth::user()->role === 'etudiant' && $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        if (Auth::user()->role === 'entreprise') {
            $entreprise = Auth::user()->entreprise;
            if (!$entreprise || $stage->entreprise_id !== $entreprise->id) {
                abort(403, 'Accès non autorisé');
            }
        }

        $stage->load(['entreprise', 'etudiant', 'candidature.offre', 'demandeStage']);

        return view('stages.show', compact('stage'));
    }

    /**
     * Crée automatiquement un stage quand une candidature est acceptée
     */
    public static function creerDepuisCandidature(Candidature $candidature)
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
     * Crée automatiquement un stage quand une demande est acceptée
     */
    public static function creerDepuisDemandeStage(DemandeStage $demande)
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

    /**
     * Démarre un stage
     */
    public function demarrer(Request $request, Stage $stage)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'etudiant' && $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if (!$stage->peutEtreDemarre()) {
            return back()->with('error', 'Ce stage ne peut pas être démarré maintenant.');
        }

        $validated = $request->validate([
            'date_debut_effective' => 'required|date|before_or_equal:today',
            'commentaire_debut' => 'nullable|string|max:1000',
        ]);

        $stage->update([
            'statut' => 'en_cours',
            'date_debut_reel' => Carbon::parse($validated['date_debut_effective']),
            'commentaire_etudiant' => $validated['commentaire_debut'] ?? null,
        ]);

        return back()->with('success', 'Stage démarré avec succès ! Vous pouvez maintenant commencer votre journal de suivi.');
    }

    /**
     * Termine un stage
     */
    public function terminer(Request $request, Stage $stage)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'etudiant' && $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if (!$stage->peutEtreTermine()) {
            return back()->with('error', 'Ce stage ne peut pas être terminé maintenant.');
        }

        $validated = $request->validate([
            'taches_realisees' => 'required|string',
            'competences_acquises' => 'nullable|string',
            'rapport_stage' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $updateData = [
            'statut' => 'termine',
            'date_fin_reel' => Carbon::now(),
            'taches_realisees' => $validated['taches_realisees'],
            'competences_acquises' => $validated['competences_acquises'] ?? null,
        ];

        // Upload du rapport de stage
        if ($request->hasFile('rapport_stage')) {
            $path = $request->file('rapport_stage')->store('stages/rapports', 'public');
            $updateData['rapport_stage'] = $path;
        }

        $stage->update($updateData);

        return back()->with('success', 'Stage terminé avec succès ! En attente d\'évaluation.');
    }

    /**
     * Évalue un stage (côté entreprise)
     */
    public function evaluer(Request $request, Stage $stage)
    {
        // Vérifier les permissions (seule l'entreprise peut évaluer)
        if (Auth::user()->role !== 'entreprise') {
            abort(403, 'Accès non autorisé');
        }

        $entreprise = Auth::user()->entreprise;
        if (!$entreprise || $stage->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        if (!$stage->peutEtreEvalue()) {
            return back()->with('error', 'Ce stage ne peut pas être évalué maintenant.');
        }

        $validated = $request->validate([
            'note_entreprise' => 'required|integer|min:0|max:20',
            'commentaire_entreprise' => 'required|string',
            'attestation_stage' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
        ]);

        $updateData = [
            'statut' => 'evalue',
            'date_evaluation' => Carbon::now(),
            'note_entreprise' => $validated['note_entreprise'],
            'commentaire_entreprise' => $validated['commentaire_entreprise'],
        ];

        // Upload de l'attestation de stage
        if ($request->hasFile('attestation_stage')) {
            $path = $request->file('attestation_stage')->store('stages/attestations', 'public');
            $updateData['attestation_stage'] = $path;
        }

        $stage->update($updateData);

        return back()->with('success', 'Stage évalué avec succès !');
    }

    /**
     * Auto-évaluation de l'étudiant
     */
    public function autoEvaluer(Request $request, Stage $stage)
    {
        // Vérifier les permissions
        if (Auth::user()->role !== 'etudiant' || $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($stage->statut !== 'evalue') {
            return back()->with('error', 'Vous ne pouvez pas vous auto-évaluer maintenant.');
        }

        $validated = $request->validate([
            'note_etudiant' => 'required|integer|min:0|max:20',
            'commentaire_etudiant' => 'required|string',
        ]);

        $stage->update([
            'statut' => 'valide',
            'note_etudiant' => $validated['note_etudiant'],
            'commentaire_etudiant' => $validated['commentaire_etudiant'],
        ]);

        return back()->with('success', 'Auto-évaluation enregistrée avec succès ! Stage validé.');
    }

    /**
     * Télécharge un document du stage
     */
    public function telechargerDocument(Stage $stage, string $type)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'etudiant' && $stage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        if (Auth::user()->role === 'entreprise') {
            $entreprise = Auth::user()->entreprise;
            if (!$entreprise || $stage->entreprise_id !== $entreprise->id) {
                abort(403, 'Accès non autorisé');
            }
        }

        $filePath = match($type) {
            'rapport' => $stage->rapport_stage,
            'attestation' => $stage->attestation_stage,
            'evaluation' => $stage->fiche_evaluation,
            default => null
        };

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Document non trouvé');
        }

        return response()->download(Storage::disk('public')->path($filePath));
    }

    /**
     * Annule un stage
     */
    public function annuler(Request $request, Stage $stage)
    {
        // Seule l'entreprise peut annuler un stage
        if (Auth::user()->role !== 'entreprise') {
            abort(403, 'Accès non autorisé');
        }

        $entreprise = Auth::user()->entreprise;
        if (!$entreprise || $stage->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        if (in_array($stage->statut, ['termine', 'evalue', 'valide'])) {
            return back()->with('error', 'Ce stage ne peut plus être annulé.');
        }

        $validated = $request->validate([
            'motif_annulation' => 'required|string|max:500'
        ]);

        $stage->update([
            'statut' => 'annule',
            'commentaire_entreprise' => $validated['motif_annulation'],
        ]);

        return back()->with('success', 'Stage annulé avec succès.');
    }

    /**
     * Liste des stages pour l'entreprise
     */
    public function indexEntreprise()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est bien une entreprise
        if ($user->role !== 'entreprise') {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé');
        }
        
        $entreprise = $user->entreprise;
        
        // Vérifier que l'entreprise existe
        if (!$entreprise) {
            return redirect()->route('dashboard')->with('error', 'Profil entreprise non trouvé');
        }
        
        $stages = Stage::where('entreprise_id', $entreprise->id)
            ->with(['etudiant', 'candidature.offre', 'demandeStage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('entreprise.stages.index', compact('stages'));
    }
}
