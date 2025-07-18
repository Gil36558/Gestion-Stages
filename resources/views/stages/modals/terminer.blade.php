<!-- Modal Terminer Stage -->
<div id="terminer-{{ $stage->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <form action="{{ route('stages.terminer', $stage) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- En-tête -->
                <div class="px-6 py-4 border-b sticky top-0 bg-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            ✅ Terminer le stage
                        </h3>
                        <button type="button" onclick="closeModal('terminer-{{ $stage->id }}')" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="px-6 py-4 space-y-6">
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-orange-800">
                                <p class="font-medium mb-1">Finalisation du stage :</p>
                                <p>Vous êtes sur le point de marquer ce stage comme terminé. Cette action permettra à l'entreprise de vous évaluer.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Date de fin effective -->
                    <div>
                        <label for="date_fin_effective_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de fin effective <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="date_fin_effective_{{ $stage->id }}" 
                               name="date_fin_effective" 
                               value="{{ date('Y-m-d') }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               required>
                    </div>

                    <!-- Tâches réalisées -->
                    <div>
                        <label for="taches_realisees_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Tâches réalisées <span class="text-red-500">*</span>
                        </label>
                        <textarea id="taches_realisees_{{ $stage->id }}" 
                                  name="taches_realisees" 
                                  rows="4"
                                  placeholder="Décrivez les principales tâches que vous avez accomplies durant ce stage..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  required>{{ old('taches_realisees', $stage->taches_realisees) }}</textarea>
                    </div>

                    <!-- Compétences acquises -->
                    <div>
                        <label for="competences_acquises_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Compétences acquises <span class="text-red-500">*</span>
                        </label>
                        <textarea id="competences_acquises_{{ $stage->id }}" 
                                  name="competences_acquises" 
                                  rows="4"
                                  placeholder="Quelles compétences avez-vous développées ou acquises ?"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  required>{{ old('competences_acquises', $stage->competences_acquises) }}</textarea>
                    </div>

                    <!-- Difficultés rencontrées -->
                    <div>
                        <label for="difficultes_rencontrees_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Difficultés rencontrées (optionnel)
                        </label>
                        <textarea id="difficultes_rencontrees_{{ $stage->id }}" 
                                  name="difficultes_rencontrees" 
                                  rows="3"
                                  placeholder="Quelles difficultés avez-vous rencontrées et comment les avez-vous surmontées ?"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('difficultes_rencontrees', $stage->difficultes_rencontrees) }}</textarea>
                    </div>

                    <!-- Rapport de stage -->
                    <div>
                        <label for="rapport_stage_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Rapport de stage <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               id="rapport_stage_{{ $stage->id }}" 
                               name="rapport_stage" 
                               accept=".pdf,.doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               {{ $stage->rapport_stage ? '' : 'required' }}>
                        <p class="text-xs text-gray-500 mt-1">
                            Formats acceptés : PDF, DOC, DOCX (max 10MB)
                        </p>
                        @if($stage->rapport_stage)
                            <p class="text-xs text-green-600 mt-1">
                                ✓ Un rapport existe déjà. Téléchargez un nouveau fichier pour le remplacer.
                            </p>
                        @endif
                    </div>

                    <!-- Commentaire de fin -->
                    <div>
                        <label for="commentaire_fin_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire de fin (optionnel)
                        </label>
                        <textarea id="commentaire_fin_{{ $stage->id }}" 
                                  name="commentaire_fin" 
                                  rows="3"
                                  placeholder="Vos impressions générales, remerciements..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('commentaire_fin') }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3 sticky bottom-0">
                    <button type="button" 
                            onclick="closeModal('terminer-{{ $stage->id }}')"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        ✅ Terminer le stage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
