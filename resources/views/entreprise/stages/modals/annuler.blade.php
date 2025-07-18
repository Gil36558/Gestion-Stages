<!-- Modal Annuler Stage -->
<div id="annuler-{{ $stage->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form action="{{ route('stages.annuler', $stage) }}" method="POST">
                @csrf
                
                <!-- En-tête -->
                <div class="px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            ❌ Annuler le stage
                        </h3>
                        <button type="button" onclick="closeModal('annuler-{{ $stage->id }}')" 
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
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <div class="text-sm text-red-800">
                                    <p class="font-medium mb-1">Attention !</p>
                                    <p>Vous êtes sur le point d'annuler le stage de <strong>{{ $stage->etudiant->name }}</strong>.</p>
                                    <p class="mt-2">Cette action est <strong>irréversible</strong> et aura les conséquences suivantes :</p>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Le stage sera marqué comme annulé</li>
                                        <li>L'étudiant sera notifié de l'annulation</li>
                                        <li>Les données du stage seront conservées pour historique</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600 mb-4">
                            <p><strong>Stage :</strong> {{ $stage->titre }}</p>
                            <p><strong>Étudiant :</strong> {{ $stage->etudiant->name }}</p>
                            <p><strong>Période :</strong> Du {{ $stage->date_debut->format('d/m/Y') }} au {{ $stage->date_fin->format('d/m/Y') }}</p>
                            <p><strong>Statut actuel :</strong> {{ $stage->statut_francais }}</p>
                        </div>
                    </div>

                    <!-- Motif d'annulation -->
                    <div class="mb-4">
                        <label for="motif_annulation_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Motif de l'annulation <span class="text-red-500">*</span>
                        </label>
                        <select id="motif_annulation_{{ $stage->id }}" 
                                name="motif_annulation" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                required>
                            <option value="">Sélectionnez un motif</option>
                            <option value="probleme_comportement">Problème de comportement</option>
                            <option value="competences_insuffisantes">Compétences insuffisantes</option>
                            <option value="absenteisme">Absentéisme répété</option>
                            <option value="non_respect_reglement">Non-respect du règlement</option>
                            <option value="reorganisation_interne">Réorganisation interne</option>
                            <option value="probleme_economique">Problème économique</option>
                            <option value="demande_etudiant">Demande de l'étudiant</option>
                            <option value="autre">Autre motif</option>
                        </select>
                    </div>

                    <!-- Commentaire détaillé -->
                    <div class="mb-4">
                        <label for="commentaire_annulation_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire détaillé <span class="text-red-500">*</span>
                        </label>
                        <textarea id="commentaire_annulation_{{ $stage->id }}" 
                                  name="commentaire_annulation" 
                                  rows="4"
                                  placeholder="Expliquez les raisons de cette annulation..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  required>{{ old('commentaire_annulation') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Ce commentaire sera visible par l'étudiant et l'administration.
                        </p>
                    </div>

                    <!-- Date d'annulation -->
                    <div class="mb-4">
                        <label for="date_annulation_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Date d'annulation effective
                        </label>
                        <input type="date" 
                               id="date_annulation_{{ $stage->id }}" 
                               name="date_annulation" 
                               value="{{ date('Y-m-d') }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <!-- Notification à l'étudiant -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="notifier_etudiant" 
                                   value="1"
                                   checked
                                   class="mr-3 text-red-600 focus:ring-red-500">
                            <span class="text-sm text-gray-700">
                                Notifier l'étudiant par email de cette annulation
                            </span>
                        </label>
                    </div>

                    <!-- Confirmation -->
                    <div class="mb-4">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   name="confirmation_annulation" 
                                   value="1"
                                   class="mr-3 mt-1 text-red-600 focus:ring-red-500"
                                   required>
                            <span class="text-sm text-gray-700">
                                Je confirme vouloir annuler définitivement ce stage et comprends que cette action est irréversible.
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeModal('annuler-{{ $stage->id }}')"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        ❌ Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
