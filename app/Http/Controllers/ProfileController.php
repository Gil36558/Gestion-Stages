<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'matricule' => 'required|string|max:50',
                'date_naissance' => 'required|date',
                'filiere' => 'required|string|max:100',
                'ecole' => 'required|string|max:100',
                'password' => 'nullable|confirmed|min:8',
            ]);

            $user->name = $validated['name'];
            $user->prenom = $validated['prenom'];
            $user->email = $validated['email'];
            $user->matricule = $validated['matricule'];
            $user->date_naissance = $validated['date_naissance'];
            $user->filiere = $validated['filiere'];
            $user->ecole = $validated['ecole'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return back()->with('status', 'Profil mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour.']);
        }
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();
        Auth::logout();
        return redirect('/');
    }
}
