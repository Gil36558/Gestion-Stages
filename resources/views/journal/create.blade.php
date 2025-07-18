@extends('layouts.app')

@section('title', 'Nouvelle Entrée de Journal')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        ✏️ Nouvelle Entrée de Journal
                    </h1>
                    <p class="text-gray-600">
                        Stage : <strong>{{ $stage->titre }}</strong> chez {{ $stage->entreprise->nom }}
                    </p>
                </div>
                <a href="{{ route('journal.index', $stage) }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    ← Retour au journal
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <form action="{{ route('journal.store', $stage) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Date de l'activité -->
                <div class="mb-6">
                    <label for="date_activite" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de l'activité <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="date_activite" 
                           name="date_activite" 
                           value="{{ old('date_activite', date('Y-m-d')) }}"
                           min="{{ $stage->date_debut->format('Y-m-d') }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_activite') border-red-500 @enderror"
                           required>
                    @error('date_activite')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        La date doit être comprise entre le début du stage et aujourd'hui
                    </p>
                </div>

                <!-- Tâches effectuées -->
                <div class="mb-6">
                    <label for="taches_effectuees" class="block text-sm font-medium text-gray-700 mb-2">
                        Tâches effectuées <span class="text-red-500">*</span>
                    </label>
                    <textarea id="taches_effectuees" 
                              name="taches_effectuees" 
                              rows="4"
                              placeholder="Décrivez en détail les tâches que vous avez accomplies aujourd'hui..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('taches_effectuees') border-red-500 @enderror"
                              required>{{ old('taches_effectuees') }}</textarea>
                    @error('taches_effectuees')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        Minimum 10 caractères. Soyez précis et détaillé.
                    </p>
                </div>

                <!-- Observations -->
                <div class="mb-6">
                    <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">
                        Observations et réflexions
                    </label>
                    <textarea id="observations" 
                              name="observations" 
                              rows="3"
                              placeholder="Vos observations sur la journée, ce qui vous a marqué..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('observations') border-red-500 @enderror">{{ old('observations') }}</textarea>
                    @error('observations')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Difficultés rencontrées -->
                <div class="mb-6">
                    <label for="difficultes_rencontrees" class="block text-sm font-medium text-gray-700 mb-2">
                        Difficultés rencontrées
                    </label>
                    <textarea id="difficultes_rencontrees" 
                              name="difficultes_rencontrees" 
                              rows="3"
                              placeholder="Les défis ou problèmes que vous avez rencontrés..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('difficultes_rencontrees') border-red-500 @enderror">{{ old('difficultes_rencontrees') }}</textarea>
                    @error('difficultes_rencontrees')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apprentissages -->
                <div class="mb-6">
                    <label for="apprentissages" class="block text-sm font-medium text-gray-700 mb-2">
                        Apprentissages et compétences développées
                    </label>
                    <textarea id="apprentissages" 
                              name="apprentissages" 
                              rows="3"
                              placeholder="Ce que vous avez appris, les compétences développées..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('apprentissages') border-red-500 @enderror">{{ old('apprentissages') }}</textarea>
                    @error('apprentissages')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heures travaillées -->
                <div class="mb-6">
                    <label for="heures_travaillees" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre d'heures travaillées
                    </label>
                    <input type="number" 
                           id="heures_travaillees" 
                           name="heures_travaillees" 
                           value="{{ old('heures_travaillees') }}"
                           min="1" 
                           max="12"
                           placeholder="8"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('heures_travaillees') border-red-500 @enderror">
                    @error('heures_travaillees')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        Entre 1 et 12 heures par jour
                    </p>
                </div>

                <!-- Fichiers joints -->
                <div class="mb-6">
                    <label for="fichiers" class="block text-sm font-medium text-gray-700 mb-2">
                        Fichiers joints (optionnel)
                    </label>
                    <input type="file" 
                           id="fichiers" 
                           name="fichiers[]" 
                           multiple
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fichiers.*') border-red-500 @enderror">
                    @error('fichiers.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        Formats acceptés : JPG, PNG, PDF, DOC, DOCX. Taille max : 5MB par fichier.
                        <br>Vous pouvez joindre des photos de votre travail, des documents créés, etc.
                    </p>
                </div>

                <!-- Statut -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Action à effectuer <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-start">
                            <input type="radio" 
                                   name="statut" 
                                   value="brouillon" 
                                   {{ old('statut', 'brouillon') === 'brouillon' ? 'checked' : '' }}
                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="font-medium text-gray-900">Sauvegarder en brouillon</div>
                                <div class="text-sm text-gray-500">
                                    Vous pourrez modifier cette entrée plus tard avant de la soumettre
                                </div>
                            </div>
                        </label>
                        
                        <label class="flex items-start">
                            <input type="radio" 
                                   name="statut" 
                                   value="soumis" 
                                   {{ old('statut') === 'soumis' ? 'checked' : '' }}
                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="font-medium text-gray-900">Soumettre directement</div>
                                <div class="text-sm text-gray-500">
                                    L'entrée sera envoyée à votre maître de stage pour validation
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('statut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conseils -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-2">💡 Conseils pour un bon journal de stage :</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Soyez précis dans la description de vos tâches</li>
                                <li>Notez vos réflexions et apprentissages du jour</li>
                                <li>N'hésitez pas à mentionner les difficultés rencontrées</li>
                                <li>Joignez des photos ou documents si pertinents</li>
                                <li>Remplissez votre journal quotidiennement</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('journal.index', $stage) }}" 
                       class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        💾 Enregistrer l'entrée
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Mise à jour du texte du bouton selon le statut sélectionné
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="statut"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'brouillon') {
                submitBtn.innerHTML = '💾 Sauvegarder en brouillon';
            } else {
                submitBtn.innerHTML = '📤 Soumettre l\'entrée';
            }
        });
    });
});
</script>
@endpush
@endsection
