<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller
{
    /**
     * Affiche les candidatures de l'étudiant connecté
     */
    public function index()
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès réservé aux étudiants');
        }

        $candidatures = Candidature::with(['offre.entreprise'])
                                  ->where('user_id', Auth::id())
                                  ->latest()
                                  ->paginate(10);

        return view('candidatures.index', compact('candidatures'));
    }

    /**
     * Affiche une candidature spécifique
     */
    public function show(Candidature $candidature)
    {
        // Vérifier que l'utilisateur peut voir cette candidature
        if (Auth::user()->role === 'etudiant' && $candidature->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        if (Auth::user()->role === 'entreprise') {
            $entreprise = Auth::user()->entreprise;
            if (!$entreprise || $candidature->offre->entreprise_id !== $entreprise->id) {
                abort(403, 'Accès non autorisé');
            }
        }

        $candidature->load(['offre.entreprise', 'user']);
        
        return view('candidatures.show', compact('candidature'));
    }

    /**
     * Enregistre une nouvelle candidature
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès réservé aux étudiants');
        }

        $validated = $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'message' => 'required|string|min:50|max:2000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'lettre' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'informations_complementaires' => 'nullable|string|max:1000',
            'date_debut_disponible' => 'nullable|date|after_or_equal:today',
            'duree_souhaitee' => 'nullable|integer|min:1|max:52',
            'competences' => 'nullable|string|max:1000',
            'experiences' => 'nullable|string|max:1000',
            'confirmation' => 'required|accepted',
        ]);

        $offre = Offre::findOrFail($validated['offre_id']);

        // Vérifier que l'offre accepte encore des candidatures
        if (!$offre->canReceiveCandidatures()) {
            return back()->with('error', 'Cette offre n\'accepte plus de candidatures.');
        }

        // Vérifier que l'étudiant n'a pas déjà candidaté
        $candidatureExistante = Candidature::where('user_id', Auth::id())
                                          ->where('offre_id', $offre->id)
                                          ->first();

        if ($candidatureExistante) {
            return back()->with('error', 'Vous avez déjà candidaté à cette offre.');
        }

        // Gérer l'upload des fichiers
        $cvPath = null;
        $lettrePath = null;

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('candidatures/cv', 'public');
        }

        if ($request->hasFile('lettre')) {
            $lettrePath = $request->file('lettre')->store('candidatures/lettres', 'public');
        }

        // Créer la candidature
        $candidature = Candidature::create([
            'user_id' => Auth::id(),
            'offre_id' => $offre->id,
            'message' => $validated['message'],
            'cv' => $cvPath,
            'lettre' => $lettrePath,
            'informations_complementaires' => $validated['informations_complementaires'],
            'date_debut_disponible' => $validated['date_debut_disponible'],
            'duree_souhaitee' => $validated['duree_souhaitee'],
            'competences' => $validated['competences'],
            'experiences' => $validated['experiences'],
            'statut' => 'en attente',
        ]);

        return redirect()->route('candidatures.show', $candidature)
                        ->with('success', 'Votre candidature a été envoyée avec succès !');
    }

    /**
     * Accepte une candidature (entreprise)
     */
    public function approve(Candidature $candidature)
    {
        if (Auth::user()->role !== 'entreprise') {
            abort(403, 'Accès réservé aux entreprises');
        }

        $entreprise = Auth::user()->entreprise;
        if (!$entreprise || $candidature->offre->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        if ($candidature->statut !== 'en attente') {
            return back()->with('error', 'Cette candidature a déjà été traitée.');
        }

        // Accepter la candidature
        $candidature->update([
            'statut' => 'acceptée',
            'date_reponse' => now(),
        ]);

        // Créer automatiquement un stage
        $stage = Stage::create([
            'user_id' => $candidature->user_id,
            'entreprise_id' => $entreprise->id,
            'candidature_id' => $candidature->id,
            'titre' => $candidature->offre->titre,
            'description' => $candidature->offre->description,
            'date_debut' => $candidature->date_debut_disponible ?? $candidature->offre->date_debut,
            'date_fin' => $candidature->offre->date_fin ?? now()->addWeeks($candidature->duree_souhaitee ?? 12),
            'lieu' => $candidature->offre->lieu,
            'statut' => 'en_attente_debut',
            'objectifs' => "Stage basé sur l'offre : " . $candidature->offre->titre,
        ]);

        return back()->with('success', 'Candidature acceptée ! Un stage a été créé automatiquement.');
    }

    /**
     * Refuse une candidature (entreprise)
     */
    public function reject(Request $request, Candidature $candidature)
    {
        if (Auth::user()->role !== 'entreprise') {
            abort(403, 'Accès réservé aux entreprises');
        }

        $entreprise = Auth::user()->entreprise;
        if (!$entreprise || $candidature->offre->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');
        }

        if ($candidature->statut !== 'en attente') {
            return back()->with('error', 'Cette candidature a déjà été traitée.');
        }

        $validated = $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);

        $candidature->update([
            'statut' => 'refusée',
            'motif_refus' => $validated['motif_refus'],
            'date_reponse' => now(),
        ]);

        return back()->with('success', 'Candidature refusée.');
    }

    /**
     * Annule une candidature (étudiant)
     */
    public function cancel(Candidature $candidature)
    {
        if (Auth::user()->role !== 'etudiant' || $candidature->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($candidature->statut !== 'en attente') {
            return back()->with('error', 'Cette candidature ne peut plus être annulée.');
        }

        $candidature->update([
            'statut' => 'annulée',
            'date_reponse' => now(),
        ]);

        return back()->with('success', 'Candidature annulée.');
    }

    /**
     * Télécharge un fichier de candidature
     */
    public function downloadFile(Candidature $candidature, $type)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'etudiant' && $candidature->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        if (Auth::user()->role === 'entreprise') {
            $entreprise = Auth::user()->entreprise;
            if (!$entreprise || $candidature->offre->entreprise_id !== $entreprise->id) {
                abort(403, 'Accès non autorisé');
            }
        }

        $filePath = null;
        $fileName = null;

        switch ($type) {
            case 'cv':
                $filePath = $candidature->cv;
                $fileName = 'CV_' . $candidature->user->name . '.pdf';
                break;
            case 'lettre':
                $filePath = $candidature->lettre;
                $fileName = 'Lettre_' . $candidature->user->name . '.pdf';
                break;
            default:
                abort(404);
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($filePath, $fileName);
    }
}
