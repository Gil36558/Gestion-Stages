<!-- Modal de candidature DEBUG -->
<div id="candidature-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <form action="{{ route('candidatures.store') }}" method="POST" enctype="multipart/form-data" id="candidature-form">
                @csrf
                <input type="hidden" name="offre_id" value="{{ $offre->id }}">
                
                <!-- En-tête -->
                <div class="px-6 py-4 border-b sticky top-0 bg-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            📝 Candidater à cette offre
                        </h3>
                        <button type="button" onclick="closeCandidatureModal()" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="px-6 py-4 space-y-6">
                    <!-- Récapitulatif de l'offre -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900 mb-2">Offre sélectionnée</h4>
                        <div class="text-sm text-blue-800">
                            <div><strong>Poste :</strong> {{ $offre->titre }}</div>
                            <div><strong>Entreprise :</strong> {{ $offre->entreprise->nom }}</div>
                            <div><strong>Lieu :</strong> {{ $offre->lieu ?? 'Non spécifié' }}</div>
                            <div><strong>Période :</strong> Du {{ $offre->date_debut->format('d/m/Y') }} 
                                @if($offre->date_fin) au {{ $offre->date_fin->format('d/m/Y') }} @endif
                            </div>
                        </div>
                    </div>

                    <!-- Message de motivation -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message de motivation <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="5"
                                  placeholder="Expliquez pourquoi vous souhaitez effectuer ce stage dans cette entreprise..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  required>{{ old('message') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Présentez-vous brièvement et expliquez votre motivation pour ce stage.
                        </p>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CV -->
                    <div>
                        <label for="cv" class="block text-sm font-medium text-gray-700 mb-2">
                            CV <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               id="cv" 
                               name="cv" 
                               accept=".pdf,.doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        <p class="text-xs text-gray-500 mt-1">
                            Formats acceptés : PDF, DOC, DOCX (max 5MB)
                        </p>
                        @error('cv')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lettre de motivation -->
                    <div>
                        <label for="lettre" class="block text-sm font-medium text-gray-700 mb-2">
                            Lettre de motivation (optionnel)
                        </label>
                        <input type="file" 
                               id="lettre" 
                               name="lettre" 
                               accept=".pdf,.doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">
                            Formats acceptés : PDF, DOC, DOCX (max 5MB)
                        </p>
                        @error('lettre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmation -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   name="confirmation" 
                                   value="1"
                                   class="mr-3 mt-1 text-blue-600 focus:ring-blue-500"
                                   required>
                            <span class="text-sm text-gray-700">
                                Je confirme que les informations fournies sont exactes et que je souhaite candidater à cette offre de stage. 
                                Je comprends que ma candidature sera transmise à l'entreprise.
                            </span>
                        </label>
                        @error('confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Debug info -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h5 class="font-medium text-yellow-800 mb-2">Debug Info:</h5>
                        <div class="text-xs text-yellow-700">
                            <div>Route: {{ route('candidatures.store') }}</div>
                            <div>Offre ID: {{ $offre->id }}</div>
                            <div>User ID: {{ auth()->id() }}</div>
                            <div>User Role: {{ auth()->user()->role }}</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3 sticky bottom-0">
                    <button type="button" 
                            onclick="closeCandidatureModal()"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        📧 Envoyer ma candidature
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('candidature-form').addEventListener('submit', function(e) {
    console.log('Form submitted!');
    console.log('Form data:', new FormData(this));
    
    // Vérifier les champs requis
    const message = document.getElementById('message').value;
    const cv = document.getElementById('cv').files[0];
    const confirmation = document.querySelector('input[name="confirmation"]').checked;
    
    console.log('Message:', message);
    console.log('CV:', cv);
    console.log('Confirmation:', confirmation);
    
    if (!message || !cv || !confirmation) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires');
        return false;
    }
});
</script>
