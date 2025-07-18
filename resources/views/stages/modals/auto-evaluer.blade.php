<!-- Modal Auto-évaluation -->
<div id="auto-evaluer-{{ $stage->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <form action="{{ route('stages.auto-evaluer', $stage) }}" method="POST">
                @csrf
                
                <!-- En-tête -->
                <div class="px-6 py-4 border-b sticky top-0 bg-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            ⭐ Auto-évaluation du stage
                        </h3>
                        <button type="button" onclick="closeModal('auto-evaluer-{{ $stage->id }}')" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="px-6 py-4 space-y-6">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-purple-800">
                                <p class="font-medium mb-1">Auto-évaluation :</p>
                                <p>Cette évaluation vous permet de faire le bilan de votre stage et de votre progression personnelle.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Note globale -->
                    <div>
                        <label for="note_etudiant_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Note globale que vous attribuez à votre stage <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            <input type="range" 
                                   id="note_etudiant_{{ $stage->id }}" 
                                   name="note_etudiant" 
                                   min="0" 
                                   max="20" 
                                   step="0.5"
                                   value="{{ old('note_etudiant', $stage->note_etudiant ?? 15) }}"
                                   class="flex-1"
                                   oninput="updateNoteDisplay('{{ $stage->id }}', this.value)"
                                   required>
                            <div class="text-2xl font-bold text-purple-600 min-w-[60px]">
                                <span id="note-display-{{ $stage->id }}">{{ old('note_etudiant', $stage->note_etudiant ?? 15) }}</span>/20
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Très insatisfaisant</span>
                            <span>Satisfaisant</span>
                            <span>Excellent</span>
                        </div>
                    </div>

                    <!-- Satisfaction générale -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Niveau de satisfaction générale <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            @foreach([
                                'tres_satisfait' => '😊 Très satisfait',
                                'satisfait' => '🙂 Satisfait', 
                                'moyennement_satisfait' => '😐 Moyennement satisfait',
                                'peu_satisfait' => '😕 Peu satisfait',
                                'insatisfait' => '😞 Insatisfait'
                            ] as $value => $label)
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="satisfaction_generale" 
                                           value="{{ $value }}"
                                           {{ old('satisfaction_generale') === $value ? 'checked' : '' }}
                                           class="mr-3 text-purple-600 focus:ring-purple-500"
                                           required>
                                    <span class="text-sm">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Objectifs atteints -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Vos objectifs de stage ont-ils été atteints ? <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            @foreach([
                                'totalement' => '✅ Totalement atteints',
                                'largement' => '👍 Largement atteints', 
                                'partiellement' => '⚡ Partiellement atteints',
                                'peu' => '⚠️ Peu atteints',
                                'pas_du_tout' => '❌ Pas du tout atteints'
                            ] as $value => $label)
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="objectifs_atteints" 
                                           value="{{ $value }}"
                                           {{ old('objectifs_atteints') === $value ? 'checked' : '' }}
                                           class="mr-3 text-purple-600 focus:ring-purple-500"
                                           required>
                                    <span class="text-sm">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recommandation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Recommanderiez-vous cette entreprise pour un stage ? <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            @foreach([
                                'fortement' => '⭐ Oui, fortement',
                                'oui' => '👍 Oui', 
                                'peut_etre' => '🤔 Peut-être',
                                'probablement_pas' => '👎 Probablement pas',
                                'non' => '❌ Non, pas du tout'
                            ] as $value => $label)
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="recommandation_entreprise" 
                                           value="{{ $value }}"
                                           {{ old('recommandation_entreprise') === $value ? 'checked' : '' }}
                                           class="mr-3 text-purple-600 focus:ring-purple-500"
                                           required>
                                    <span class="text-sm">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Points positifs -->
                    <div>
                        <label for="points_positifs_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Points positifs de ce stage <span class="text-red-500">*</span>
                        </label>
                        <textarea id="points_positifs_{{ $stage->id }}" 
                                  name="points_positifs" 
                                  rows="4"
                                  placeholder="Qu'avez-vous le plus apprécié dans ce stage ?"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  required>{{ old('points_positifs') }}</textarea>
                    </div>

                    <!-- Points à améliorer -->
                    <div>
                        <label for="points_ameliorer_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Points à améliorer (optionnel)
                        </label>
                        <textarea id="points_ameliorer_{{ $stage->id }}" 
                                  name="points_ameliorer" 
                                  rows="3"
                                  placeholder="Que pourrait améliorer l'entreprise pour les futurs stagiaires ?"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('points_ameliorer') }}</textarea>
                    </div>

                    <!-- Commentaire général -->
                    <div>
                        <label for="commentaire_etudiant_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire général sur votre expérience <span class="text-red-500">*</span>
                        </label>
                        <textarea id="commentaire_etudiant_{{ $stage->id }}" 
                                  name="commentaire_etudiant" 
                                  rows="4"
                                  placeholder="Décrivez votre expérience globale, ce que vous retenez de ce stage..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  required>{{ old('commentaire_etudiant', $stage->commentaire_etudiant) }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3 sticky bottom-0">
                    <button type="button" 
                            onclick="closeModal('auto-evaluer-{{ $stage->id }}')"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        ⭐ Enregistrer l'évaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateNoteDisplay(stageId, value) {
    document.getElementById('note-display-' + stageId).textContent = value;
}
</script>
