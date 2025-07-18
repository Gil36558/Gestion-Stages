<!-- Modal Démarrer Stage -->
<div id="demarrer-{{ $stage->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form action="{{ route('stages.demarrer', $stage) }}" method="POST">
                @csrf
                
                <!-- En-tête -->
                <div class="px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            🚀 Démarrer le stage
                        </h3>
                        <button type="button" onclick="closeModal('demarrer-{{ $stage->id }}')" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <p class="text-gray-600 mb-4">
                            Vous êtes sur le point de démarrer votre stage "<strong>{{ $stage->titre }}</strong>" 
                            chez <strong>{{ $stage->entreprise->nom }}</strong>.
                        </p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">Informations importantes :</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>La date de début sera enregistrée comme aujourd'hui</li>
                                        <li>Vous pourrez suivre votre progression</li>
                                        <li>Pensez à tenir un journal de vos activités</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date de début effective -->
                    <div class="mb-4">
                        <label for="date_debut_effective_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de début effective
                        </label>
                        <input type="date" 
                               id="date_debut_effective_{{ $stage->id }}" 
                               name="date_debut_effective" 
                               value="{{ date('Y-m-d') }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        <p class="text-xs text-gray-500 mt-1">
                            La date ne peut pas être dans le futur
                        </p>
                    </div>

                    <!-- Commentaire optionnel -->
                    <div class="mb-4">
                        <label for="commentaire_debut_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire (optionnel)
                        </label>
                        <textarea id="commentaire_debut_{{ $stage->id }}" 
                                  name="commentaire_debut" 
                                  rows="3"
                                  placeholder="Vos premières impressions, objectifs personnels..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeModal('demarrer-{{ $stage->id }}')"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        🚀 Démarrer le stage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
