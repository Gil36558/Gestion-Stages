<?php

namespace App\Http\Controllers;

use App\Models\DemandeStage;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use App\Notifications\BinomeAjouteNotification;

class DemandeStageController extends Controller
{
    public function choixType(Request $request)
    {
        if (!$request->has('entreprise_id')) {
            return redirect()->route('entreprises.index')->with('error', 'Entreprise non spécifiée.');
        }

        return view('etudiant.demande.choix_type');
    }

    public function form(Request $request)
    {
        $type = $request->query('type');
        $entrepriseId = $request->query('entreprise_id');

        if (!in_array($type, ['academique', 'professionnel']) || !$entrepriseId) {
            return redirect()->route('demande.stage.choix', ['entreprise_id' => $entrepriseId])
                             ->with('error', 'Informations de demande invalides.');
        }

        $entreprise = Entreprise::findOrFail($entrepriseId);

        return view('etudiant.demande.form', compact('type', 'entreprise'));
    }

    public function store(Request $request)
    {
        $type = $request->input('type');
        $baseRules = [
            'type' => ['required', Rule::in(['academique', 'professionnel'])],
            'entreprise_id' => ['required', 'exists:entreprises,id'],
            'date_debut_souhaitee' => ['required', 'date'],
            'date_fin_souhaitee' => ['required', 'date', 'after_or_equal:date_debut_souhaitee'],
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'lettre_motivation' => ['nullable', 'file', 'mimes:pdf,docx', 'max:5120'],
            'recommandation' => ['nullable', 'file', 'mimes:pdf,docx', 'max:5120'],
            'piece_identite' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'portfolio' => ['nullable', 'file', 'mimes:pdf,zip,rar', 'max:10240'],
            'email_binome' => ['nullable', 'email'],
        ];

        if ($type === 'academique') {
            $baseRules = array_merge($baseRules, [
                'mode' => ['required', Rule::in(['solo', 'binome'])],
                'periode' => 'required|string',
                'objectifs_stage' => 'required|string',
                'recommandation' => 'required|file|mimes:pdf,docx|max:5120',
                'cv' => 'required|file|mimes:pdf|max:5120',
                'lettre_motivation' => 'required|file|mimes:pdf,docx|max:5120'
            ]);

            if ($request->input('mode') === 'binome') {
                $baseRules['email_binome'] = ['required', 'email', 'different:' . Auth::user()->email];
                $baseRules['nom_binome'] = ['required', 'string', 'max:255'];
            }
        } elseif ($type === 'professionnel') {
            $baseRules = array_merge($baseRules, [
                'date_debut_disponible' => 'required|date',
                'cv' => 'required|file|mimes:pdf|max:5120',
                'lettre_motivation' => 'required|file|mimes:pdf,docx|max:5120'
            ]);
        }

        $request->validate($baseRules);

        // Upload des fichiers avec vérification explicite
        $cv = null;
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            $cv = $request->file('cv')->store('demandes/cv', 'public');
        }

        $lettre = null;
        if ($request->hasFile('lettre_motivation') && $request->file('lettre_motivation')->isValid()) {
            $lettre = $request->file('lettre_motivation')->store('demandes/lettres', 'public');
        }

        $recommandation = null;
        if ($request->hasFile('recommandation') && $request->file('recommandation')->isValid()) {
            $recommandation = $request->file('recommandation')->store('demandes/recommandations', 'public');
        }

        $piece = null;
        if ($request->hasFile('piece_identite') && $request->file('piece_identite')->isValid()) {
            $piece = $request->file('piece_identite')->store('demandes/pieces', 'public');
        }

        $portfolio = null;
        if ($request->hasFile('portfolio') && $request->file('portfolio')->isValid()) {
            $portfolio = $request->file('portfolio')->store('demandes/portfolios', 'public');
        }

        $donnees = $request->except([
            'cv', 'lettre_motivation', 'recommandation', 'piece_identite', 'portfolio', 'email_binome', 'date_debut_souhaitee', 'date_fin_souhaitee'
        ]);

        // Ajouter les chemins des fichiers seulement s'ils existent
        if ($cv) $donnees['cv'] = $cv;
        if ($lettre) $donnees['lettre_motivation'] = $lettre;
        if ($recommandation) $donnees['recommandation'] = $recommandation;
        if ($piece) $donnees['piece_identite'] = $piece;
        if ($portfolio) $donnees['portfolio'] = $portfolio;

        $donnees['periode_debut'] = $request->date_debut_souhaitee;
        $donnees['periode_fin'] = $request->date_fin_souhaitee;
        $donnees['objet'] = $request->input('objet') ?? null;

        $demande = DemandeStage::create($donnees);

        $demande->etudiants()->attach(Auth::id());

        if ($type === 'academique' && $request->mode === 'binome') {
            $binome = User::where('email', $request->email_binome)->first();
            if ($binome && $binome->id !== Auth::id()) {
                $demande->etudiants()->attach($binome->id);
                $binome->notify(new BinomeAjouteNotification($demande));
            }
        }

        return redirect()->route('etudiant.dashboard')->with('success', 'Demande envoyée avec succès !');
    }

    /**
     * Affiche les détails d'une demande de stage
     */
    public function show(DemandeStage $demande)
    {
        // Vérifier que l'utilisateur peut voir cette demande
        $user = auth()->user();
        
        // Si c'est une entreprise, vérifier qu'elle est propriétaire de la demande
        if ($user->estEntreprise()) {
            $entreprise = $user->entreprise;
            if (!$entreprise || $demande->entreprise_id !== $entreprise->id) {
                abort(403, 'Accès non autorisé');
            }
        }
        // Si c'est un étudiant, vérifier qu'il est lié à cette demande
        elseif ($user->estEtudiant()) {
            if (!$demande->etudiants->contains($user->id)) {
                abort(403, 'Accès non autorisé');
            }
        }
        // Si c'est un admin, accès autorisé
        elseif (!$user->estAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        // Charger les relations nécessaires
        $demande->load(['entreprise', 'etudiants', 'offre']);

        return view('demandes.show', compact('demande'));
    }

    public function verifierBinome(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nom' => 'required|string'
        ]);

        $email = $request->input('email');
        $nom = $request->input('nom');

        // Vérifier que ce n'est pas l'utilisateur actuel
        if ($email === Auth::user()->email) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas vous ajouter comme binôme.'
            ]);
        }

        // Chercher l'utilisateur avec l'email et le nom
        $binome = User::where('email', $email)
                     ->where('role', 'etudiant')
                     ->first();

        if (!$binome) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun étudiant trouvé avec cet email. Assurez-vous que votre binôme s\'est bien inscrit.'
            ]);
        }

        // Vérifier si le nom correspond (recherche flexible)
        $nomBinome = strtolower(trim($binome->name));
        $nomRecherche = strtolower(trim($nom));

        // Vérification flexible du nom (contient ou est contenu)
        if (strpos($nomBinome, $nomRecherche) === false && strpos($nomRecherche, $nomBinome) === false) {
            return response()->json([
                'success' => false,
                'message' => 'L\'email existe mais le nom ne correspond pas. Nom enregistré : ' . $binome->name
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Binôme trouvé : ' . $binome->name . ' (' . $binome->email . ')',
            'binome' => [
                'id' => $binome->id,
                'name' => $binome->name,
                'email' => $binome->email
            ]
        ]);
    }

    public function create()
    {
        abort(404);
    }
}
