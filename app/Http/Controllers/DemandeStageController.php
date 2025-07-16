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
            'cv' => ['nullable', 'file', 'mimes:pdf'],
            'lettre_motivation' => ['nullable', 'file', 'mimes:pdf,docx'],
            'recommandation' => ['nullable', 'file', 'mimes:pdf,docx'],
            'piece_identite' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png'],
            'portfolio' => ['nullable', 'file', 'mimes:pdf,zip,rar'],
            'email_binome' => ['nullable', 'email'],
        ];

        if ($type === 'academique') {
            $baseRules = array_merge($baseRules, [
                'mode' => ['required', Rule::in(['solo', 'binome'])],
                'periode' => 'required|string',
                'objectifs_stage' => 'required|string',
                'recommandation' => 'required|file|mimes:pdf,docx',
                'cv' => 'required|file|mimes:pdf',
                'lettre_motivation' => 'required|file|mimes:pdf,docx'
            ]);

            if ($request->input('mode') === 'binome') {
                $baseRules['email_binome'] = ['required', 'email', 'different:' . Auth::user()->email];
            }
        } elseif ($type === 'professionnel') {
            $baseRules = array_merge($baseRules, [
                'date_debut_disponible' => 'required|date',
                'cv' => 'required|file|mimes:pdf',
                'lettre_motivation' => 'required|file|mimes:pdf,docx'
            ]);
        }

        $request->validate($baseRules);

        $cv = $request->file('cv')?->store('demandes/cv', 'public');
        $lettre = $request->file('lettre_motivation')?->store('demandes/lettres', 'public');
        $recommandation = $request->file('recommandation')?->store('demandes/recommandations', 'public');
        $piece = $request->file('piece_identite')?->store('demandes/pieces', 'public');
        $portfolio = $request->file('portfolio')?->store('demandes/portfolios', 'public');

        $donnees = $request->except([
            'cv', 'lettre_motivation', 'recommandation', 'piece_identite', 'portfolio', 'email_binome', 'date_debut_souhaitee', 'date_fin_souhaitee'
        ]);

        $donnees['cv'] = $cv;
        $donnees['lettre_motivation'] = $lettre;
        $donnees['recommandation'] = $recommandation;
        $donnees['piece_identite'] = $piece;
        $donnees['portfolio'] = $portfolio;
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

    public function create()
    {
        abort(404);
    }
}


