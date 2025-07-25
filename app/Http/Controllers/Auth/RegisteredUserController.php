<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entreprise;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Règles de base communes
        $baseRules = [
            'role' => ['required', 'in:etudiant,entreprise'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($request->role === 'etudiant') {
            $rules = array_merge($baseRules, [
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'telephone' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'nom' => ['required', 'string', 'max:255'],
                'universite' => ['required', 'string', 'max:255'], // sera mappé sur ecole
                'domaine' => ['required', 'string', 'max:255'],    // sera mappé sur filiere
                'niveau' => ['required', 'string', 'max:255'],     // niveau d'études
                'matricule' => ['nullable', 'string', 'max:255'],
                'date_naissance' => ['nullable', 'date'],
            ]);
        } else {
            $rules = array_merge($baseRules, [
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'telephone' => ['required', 'string', 'max:255'],
                'nom_entreprise' => ['required', 'string', 'max:255'],
                'secteur' => ['required', 'string', 'max:255'],
                'taille' => ['required', 'string', 'max:255'], // ignoré car pas en BDD
                'adresse' => ['required', 'string', 'max:255'],
                'site_web' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);
        }

        $validated = $request->validate($rules);

        // Création du user
        $user = User::create([
            'name' => $request->role === 'entreprise' ? $request->nom_entreprise : $request->nom,
            'prenom' => $request->prenom ?? null,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse ?? null,
            'matricule' => $request->matricule ?? null,
            'filiere' => $request->domaine ?? null,
            'ecole' => $request->universite ?? null,
            'niveau' => $request->niveau ?? null,
            'date_naissance' => $request->date_naissance ?? null,
        ]);

        // Si entreprise, créer une fiche associée
        if ($request->role === 'entreprise') {
            Entreprise::create([
                'user_id' => $user->id,
                'nom' => $request->nom_entreprise,
                'email' => $request->email,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'secteur' => $request->secteur,
                'site_web' => $request->site_web,
                'description' => $request->description,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
