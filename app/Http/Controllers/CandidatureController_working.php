<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        // Log pour debug
        Log::info('=== CANDIDATURE STORE DEBUG ===');
        Log::info('User ID: ' . Auth::id());
        Log::info('User Role: ' . Auth::user()->role);
        Log::info('Request data: ', $request->all());
        Log::info('Files: ', $request->allFiles());

        if (Auth::user()->role !== 'etudiant') {
            Log::error('Accès refusé - rôle incorrect: ' . Auth::user()->role);
            abort(403, 'Accès réservé aux étudiants');
        }

        // Validation simplifiée pour le debug
        $validated = $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'message' => 'required|string|min:10|max:2000', // Réduit à 10 caractères minimum
            'cv' => 'required|file|mimes:pdf,doc,docx,txt|max:5120', // Ajout de txt pour les tests
            'lettre' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
            'confirmation' => 'required|accepted',
        ]);

        Log::info('Validation passée');

        $offre = Offre::findOrFail($validated['offre_id']);
        Log::info('Offre trouvée: ' . $offre->titre);

        // Vérifier que l'offre accepte encore des candidatures
        if (!$offre->canReceiveCandidatures()) {
            Log::error('Offre n\'accepte plus de candidatures');
            return back()->with('error', 'Cette offre n\'accepte plus de candidatures.');
        }

        // Vérifier que l'étudiant n'a pas déjà candidaté
        $candidatureExistante = Candidature::where('user_id', Auth::id())
                                          ->where('offre_id', $offre->id)
                                          ->first();

        if ($candidatureExistante) {
            Log::error('Candidature existante trouvée');
            return back()->with('error', 'Vous avez déjà candidaté à cette offre.');
        }

        // Gérer l'upload des fichiers avec vérification
        $cvPath = null;
        $lettrePath = null;

        // Upload du CV (requis)
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            try {
                $cvPath = $request->file('cv')->store('candidatures/cv', 'public');
                Log::info('CV uploadé: ' . $cvPath);
            } catch (\Exception $e) {
                Log::error('Erreur upload CV: ' . $e->getMessage());
                return back()->withErrors(['cv' => 'Erreur lors de l\'upload du CV.'])
                            ->withInput();
            }
        } else {
            Log::error('CV manquant ou invalide');
            return back()->withErrors(['cv' => 'Le fichier CV est requis et doit être valide.'])
                        ->withInput();
        }

        // Upload de la lettre (optionnel)
        if ($request->hasFile('lettre') && $request->file('lettre')->isValid()) {
            try {
                $lettrePath = $request->file('lettre')->store('candidatures/lettres', 'public');
                Log::info('Lettre uploadée: ' . $lettrePath);
            } catch (\Exception $e) {
                Log::error('Erreur upload lettre: ' . $e->getMessage());
                // Continue même si la lettre échoue car elle est optionnelle
            }
        }

        // Créer la candidature
        try {
            $candidature = Candidature::create([
                'user_id' => Auth::id(),
                'offre_id' => $offre->id,
                'message' => $validated['message'],
                'cv' => $cvPath,
                'lettre' => $lettrePath,
                'statut' => 'en attente',
            ]);

            Log::info('Candidature créée avec succès: ID ' . $candidature->id);

            return redirect()->route('candidatures.show', $candidature)
                            ->with('success', 'Votre candidature a été envoyée avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur création candidature: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Nettoyer les fichiers uploadés en cas d'erreur
            if ($cvPath && Storage::disk('public')->exists($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }
            if ($lettrePath && Storage::disk('public')->exists($lettrePath)) {
                Storage::disk('public')->delete($lettrePath);
            }
            
            return back()->with('error', 'Erreur lors de l\'enregistrement de la candidature: ' . $e->getMessage())
                        ->withInput();
        }
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

        // Créer automatiquement un stage si le modèle Stage existe
        if (class_exists('App\Models\Stage')) {
            try {
                $stage = Stage::create([
                    'user_id' => $candidature->user_id,
                    'entreprise_id' => $entreprise->id,
                    'candidature_id' => $candidature->id,
                    'titre' => $candidature->offre->titre,
                    'description' => $candidature->offre->description,
                    'date_debut' => $candidature->date_debut_disponible ?? $candidature->offre->date_debut,
                    'date_fin' => $candidature->offre->date_fin ?? now()->addWeeks(12),
                    'lieu' => $candidature->offre->lieu,
                    'statut' => 'en_attente_debut',
                    'objectifs' => "Stage basé sur l'offre : " . $candidature->offre->titre,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur création stage: ' . $e->getMessage());
                // Continue même si la création du stage échoue
            }
        }

        return back()->with('success', 'Candidature acceptée !');
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

        return response()->download(Storage::disk('public')->path($filePath), $fileName);
    }
}
