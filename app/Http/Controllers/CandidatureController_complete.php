<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\CandidatureRecueNotification;
use App\Notifications\CandidatureEnvoyeeNotification;

class CandidatureController_complete extends Controller
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
     * Enregistre une nouvelle candidature - VERSION CORRIGÉE
     */
    public function store(Request $request)
    {
        // Log pour debug
        Log::info('=== CANDIDATURE STORE COMPLETE ===');
        Log::info('User ID: ' . Auth::id());
        Log::info('User Role: ' . Auth::user()->role);
        Log::info('Request data: ', $request->all());
        Log::info('Files: ', $request->allFiles());

        if (Auth::user()->role !== 'etudiant') {
            Log::error('Accès refusé - rôle incorrect: ' . Auth::user()->role);
            abort(403, 'Accès réservé aux étudiants');
        }

        // Validation COMPLÈTE avec tous les champs du formulaire
        $validated = $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'message' => 'required|string|min:5|max:2000', // Réduit le minimum
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'lettre' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'confirmation' => 'required|accepted',
            
            // Champs supplémentaires du formulaire
            'informations_complementaires' => 'nullable|string|max:1000',
            'date_debut_disponible' => 'nullable|date',
            'duree_souhaitee' => 'nullable|integer|min:1|max:52',
            'competences' => 'nullable|string|max:1000',
            'experiences' => 'nullable|string|max:1000',
        ]);

        Log::info('Validation passée avec succès');

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

        // Gérer l'upload des fichiers avec vérification renforcée
        $cvPath = null;
        $lettrePath = null;

        // Upload du CV (requis)
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            try {
                $cvFile = $request->file('cv');
                $cvPath = $cvFile->store('candidatures/cv', 'public');
                Log::info('CV uploadé avec succès: ' . $cvPath);
            } catch (\Exception $e) {
                Log::error('Erreur upload CV: ' . $e->getMessage());
                return back()->withErrors(['cv' => 'Erreur lors de l\'upload du CV: ' . $e->getMessage()])
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
                $lettreFile = $request->file('lettre');
                $lettrePath = $lettreFile->store('candidatures/lettres', 'public');
                Log::info('Lettre uploadée avec succès: ' . $lettrePath);
            } catch (\Exception $e) {
                Log::error('Erreur upload lettre: ' . $e->getMessage());
                // Continue même si la lettre échoue car elle est optionnelle
            }
        }

        // Créer la candidature avec TOUS les champs
        try {
            $candidatureData = [
                'user_id' => Auth::id(),
                'offre_id' => $offre->id,
                'message' => $validated['message'],
                'cv' => $cvPath,
                'lettre' => $lettrePath,
                'statut' => 'en attente',
                
                // Champs supplémentaires
                'informations_complementaires' => $validated['informations_complementaires'] ?? null,
                'date_debut_disponible' => $validated['date_debut_disponible'] ?? null,
                'duree_souhaitee' => $validated['duree_souhaitee'] ?? null,
                'competences' => $validated['competences'] ?? null,
                'experiences' => $validated['experiences'] ?? null,
            ];

            $candidature = Candidature::create($candidatureData);

            Log::info('Candidature créée avec succès: ID ' . $candidature->id);

            // Envoyer les notifications
            try {
                // Notification à l'étudiant
                Auth::user()->notify(new CandidatureEnvoyeeNotification($candidature));
                
                // Notification à l'entreprise
                if ($offre->entreprise && $offre->entreprise->user) {
                    $offre->entreprise->user->notify(new CandidatureRecueNotification($candidature));
                }
                
                Log::info('Notifications envoyées avec succès');
            } catch (\Exception $e) {
                Log::error('Erreur envoi notifications: ' . $e->getMessage());
                // Continue même si les notifications échouent
            }

            return redirect()->route('candidatures.show', $candidature)
                            ->with('success', 'Votre candidature a été envoyée avec succès ! L\'entreprise a été notifiée.');

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

        // Envoyer notification à l'étudiant
        try {
            $candidature->user->notify(new \App\Notifications\CandidatureAccepteeNotification($candidature));
        } catch (\Exception $e) {
            Log::error('Erreur notification acceptation: ' . $e->getMessage());
        }

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
                
                Log::info('Stage créé automatiquement: ID ' . $stage->id);
            } catch (\Exception $e) {
                Log::error('Erreur création stage: ' . $e->getMessage());
                // Continue même si la création du stage échoue
            }
        }

        return back()->with('success', 'Candidature acceptée ! L\'étudiant a été notifié.');
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

        // Envoyer notification à l'étudiant
        try {
            $candidature->user->notify(new \App\Notifications\CandidatureRefuseeNotification($candidature));
        } catch (\Exception $e) {
            Log::error('Erreur notification refus: ' . $e->getMessage());
        }

        return back()->with('success', 'Candidature refusée. L\'étudiant a été notifié.');
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
