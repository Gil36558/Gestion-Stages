<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
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

        $candidatures = Auth::user()->candidatures()
                           ->with(['offre.entreprise'])
                           ->latest()
                           ->paginate(10);

        return view('candidatures.index', compact('candidatures'));
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
            'cv' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'lettre' => 'required|file|mimes:pdf,docx|max:5120',
            'message' => 'nullable|string|max:1000',
        ]);

        $offre = Offre::findOrFail($validated['offre_id']);

        // Vérifier si l'offre peut recevoir des candidatures
        if (!$offre->canReceiveCandidatures()) {
            return back()->with('error', 'Cette offre n\'accepte plus de candidatures.');
        }

        // Vérifier si l'étudiant n'a pas déjà candidaté
        $existingCandidature = Candidature::where('user_id', Auth::id())
                                         ->where('offre_id', $offre->id)
                                         ->first();

        if ($existingCandidature) {
            return back()->with('error', 'Vous avez déjà candidaté pour cette offre.');
        }

        // Upload des fichiers
        $cvPath = $request->file('cv')->store('candidatures/cv', 'public');
        $lettrePath = $request->file('lettre')->store('candidatures/lettres', 'public');

        // Créer la candidature
        Candidature::create([
            'user_id' => Auth::id(),
            'offre_id' => $offre->id,
            'cv' => $cvPath,
            'lettre' => $lettrePath,
            'message' => $validated['message'] ?? null,
            'statut' => 'en attente',
        ]);

        return redirect()->route('candidatures.index')
                        ->with('success', 'Votre candidature a été envoyée avec succès !');
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
     * Supprime une candidature (étudiant uniquement)
     */
    public function destroy(Candidature $candidature)
    {
        if (Auth::user()->role !== 'etudiant' || $candidature->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Ne peut supprimer que si la candidature est en attente
        if ($candidature->statut !== 'en attente') {
            return back()->with('error', 'Vous ne pouvez pas supprimer une candidature déjà traitée.');
        }

        // Supprimer les fichiers
        if ($candidature->cv) {
            Storage::disk('public')->delete($candidature->cv);
        }
        if ($candidature->lettre) {
            Storage::disk('public')->delete($candidature->lettre);
        }

        $candidature->delete();

        return redirect()->route('candidatures.index')
                        ->with('success', 'Candidature supprimée avec succès.');
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
                abort(404, 'Fichier non trouvé');
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }

        return response()->download(storage_path('app/public/' . $filePath), $fileName);
    }
}
