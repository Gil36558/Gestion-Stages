@extends('layouts.app')

@section('title', 'Administration - Modifier un utilisateur')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Modifier l'utilisateur</h1>
                    <p class="text-gray-600">{{ $user->name }} - {{ $user->email }}</p>
                </div>
                <a href="{{ route('admin.utilisateurs') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <form method="POST" action="{{ route('admin.utilisateurs.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- Informations de base -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de base</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom complet *
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prénom
                                </label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                                @error('prenom')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rôle *
                                </label>
                                <select id="role" name="role" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror">
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="etudiant" {{ old('role', $user->role) === 'etudiant' ? 'selected' : '' }}>🎓 Étudiant</option>
                                    <option value="entreprise" {{ old('role', $user->role) === 'entreprise' ? 'selected' : '' }}>🏢 Entreprise</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>👑 Administrateur</option>
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Mot de passe (optionnel) -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Changer le mot de passe (optionnel)</h3>
                        <p class="text-sm text-gray-600 mb-4">Laissez vide pour conserver le mot de passe actuel</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nouveau mot de passe
                                </label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmer le mot de passe
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations supplémentaires</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Téléphone
                                </label>
                                <input type="tel" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                                @error('telephone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date de naissance
                                </label>
                                <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance?->format('Y-m-d')) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                                @error('date_naissance')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse
                            </label>
                            <textarea id="adresse" name="adresse" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror">{{ old('adresse', $user->adresse) }}</textarea>
                            @error('adresse')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations spécifiques aux étudiants -->
                    <div id="etudiant-fields" class="mb-8" style="{{ $user->role === 'etudiant' ? 'display: block;' : 'display: none;' }}">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations étudiant</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="matricule" class="block text-sm font-medium text-gray-700 mb-2">
                                    Matricule
                                </label>
                                <input type="text" id="matricule" name="matricule" value="{{ old('matricule', $user->matricule) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('matricule') border-red-500 @enderror">
                                @error('matricule')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="filiere" class="block text-sm font-medium text-gray-700 mb-2">
                                    Filière
                                </label>
                                <input type="text" id="filiere" name="filiere" value="{{ old('filiere', $user->filiere) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('filiere') border-red-500 @enderror">
                                @error('filiere')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="ecole" class="block text-sm font-medium text-gray-700 mb-2">
                                École/Université
                            </label>
                            <input type="text" id="ecole" name="ecole" value="{{ old('ecole', $user->ecole) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ecole') border-red-500 @enderror">
                            @error('ecole')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations sur le compte -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations du compte</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Créé le :</span>
                                <span class="font-medium">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Dernière modification :</span>
                                <span class="font-medium">{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @if($user->email_verified_at)
                                <div>
                                    <span class="text-gray-600">Email vérifié le :</span>
                                    <span class="font-medium text-green-600">{{ $user->email_verified_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            @else
                                <div>
                                    <span class="text-red-600">Email non vérifié</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('admin.utilisateurs') }}" 
                           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            ✅ Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('role').addEventListener('change', function() {
    const etudiantFields = document.getElementById('etudiant-fields');
    if (this.value === 'etudiant') {
        etudiantFields.style.display = 'block';
    } else {
        etudiantFields.style.display = 'none';
    }
});
</script>
@endpush
@endsection
