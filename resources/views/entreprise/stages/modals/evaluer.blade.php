<!-- Modal Évaluer Stage -->
<div id="evaluer-{{ $stage->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <form action="{{ route('stages.evaluer', $stage) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- En-tête -->
                <div class="px-6 py-4 border-b sticky top-0 bg-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            ⭐ Évaluer le stagiaire
                        </h3>
                        <button type="button" onclick="closeModal('evaluer-{{ $stage->id }}')" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="px-6 py-4 space-y-6">
                    <!-- Informations du stagiaire -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900 mb-2">Stagiaire à évaluer</h4>
                        <div class="text-sm text-blue-800">
                            <div><strong>Nom :</strong> {{ $stage->etudiant->name }}</div>
                            @if($stage->etudiant->filiere)
                                <div><strong>Filière :</strong> {{ $stage->etudiant->filiere }}</div>
                            @endif
                            <div><strong>Stage :</strong> {{ $stage->titre }}</div>
                            <div><strong>Période :</strong> Du {{ $stage->date_debut->format('d/m/Y') }} au {{ $stage->date_fin->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <!-- Note globale -->
                    <div>
                        <label for="note_entreprise_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Note globale attribuée au stagiaire <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            <input type="range" 
                                   id="note_entreprise_{{ $stage->id }}" 
                                   name="note_entreprise" 
                                   min="0" 
                                   max="20" 
                                   step="0.5"
                                   value="{{ old('note_entreprise', $stage->note_entreprise ?? 15) }}"
                                   class="flex-1"
                                   oninput="updateNoteDisplayEntreprise('{{ $stage->id }}', this.value)"
                                   required>
                            <div class="text-2xl font-bold text-purple-600 min-w-[60px]">
                                <span id="note-display-entreprise-{{ $stage->id }}">{{ old('note_entreprise', $stage->note_entreprise ?? 15) }}</span>/20
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Très insuffisant</span>
                            <span>Satisfaisant</span>
                            <span>Excellent</span>
                        </div>
                    </div>

                    <!-- Évaluation des compétences -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Évaluation des compétences <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-4">
                            @foreach([
                                'competences_techniques' => 'Compétences techniques',
                                'autonomie' => 'Autonomie et initiative',
                                'communication' => 'Communication',
                                'integration_equipe' => 'Intégration dans l\'équipe',
                                'respect_consignes' => 'Respect des consignes',
                                'ponctualite' => 'Ponctualité et assiduité'
                            ] as $competence => $label)
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700 flex-1">{{ $label }}</label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="evaluation_{{ $competence }}" 
                                                       value="{{ $i }}"
                                                       class="sr-only"
                                                       required>
                                                <svg class="w-6 h-6 text-gray-300 hover:text-yellow-400 cursor-pointer star-rating" 
                                                     data-rating="{{ $i }}" 
                                                     data-competence="{{ $competence }}"
                                                     fill="currentColor" 
                                                     viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recommandation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Recommanderiez-vous ce stagiaire ? <span class="text-red-500">*</span>
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
                                           name="recommandation_stagiaire" 
                                           value="{{ $value }}"
                                           {{ old('recommandation_stagiaire') === $value ? 'checked' : '' }}
                                           class="mr-3 text-purple-600 focus:ring-purple-500"
                                           required>
                                    <span class="text-sm">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Points forts -->
                    <div>
                        <label for="points_forts_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Points forts du stagiaire <span class="text-red-500">*</span>
                        </label>
                        <textarea id="points_forts_{{ $stage->id }}" 
                                  name="points_forts" 
                                  rows="3"
                                  placeholder="Quelles sont les qualités principales de ce stagiaire ?"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  required>{{ old('points_forts') }}</textarea>
                    </div>

                    <!-- Points à améliorer -->
                    <div>
                        <label for="points_ameliorer_stagiaire_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Points à améliorer (optionnel)
                        </label>
                        <textarea id="points_ameliorer_stagiaire_{{ $stage->id }}" 
                                  name="points_ameliorer_stagiaire" 
                                  rows="3"
                                  placeholder="Quels aspects le stagiaire pourrait-il améliorer ?"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('points_ameliorer_stagiaire') }}</textarea>
                    </div>

                    <!-- Commentaire général -->
                    <div>
                        <label for="commentaire_entreprise_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire général sur le stagiaire <span class="text-red-500">*</span>
                        </label>
                        <textarea id="commentaire_entreprise_{{ $stage->id }}" 
                                  name="commentaire_entreprise" 
                                  rows="4"
                                  placeholder="Votre évaluation générale du stagiaire et de son travail..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  required>{{ old('commentaire_entreprise', $stage->commentaire_entreprise) }}</textarea>
                    </div>

                    <!-- Attestation de stage -->
                    <div>
                        <label for="attestation_stage_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Attestation de stage (optionnel)
                        </label>
                        <input type="file" 
                               id="attestation_stage_{{ $stage->id }}" 
                               name="attestation_stage" 
                               accept=".pdf,.doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <p class="text-xs text-gray-500 mt-1">
                            Formats acceptés : PDF, DOC, DOCX (max 5MB)
                        </p>
                        @if($stage->attestation_stage)
                            <p class="text-xs text-green-600 mt-1">
                                ✓ Une attestation existe déjà. Téléchargez un nouveau fichier pour la remplacer.
                            </p>
                        @endif
                    </div>

                    <!-- Informations du maître de stage -->
                    <div class="border-t pt-4">
                        <h4 class="font-medium text-gray-900 mb-4">Informations du maître de stage</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="maitre_stage_nom_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom du maître de stage
                                </label>
                                <input type="text" 
                                       id="maitre_stage_nom_{{ $stage->id }}" 
                                       name="maitre_stage_nom" 
                                       value="{{ old('maitre_stage_nom', $stage->maitre_stage_nom) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label for="maitre_stage_poste_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Poste
                                </label>
                                <input type="text" 
                                       id="maitre_stage_poste_{{ $stage->id }}" 
                                       name="maitre_stage_poste" 
                                       value="{{ old('maitre_stage_poste', $stage->maitre_stage_poste) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label for="maitre_stage_email_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" 
                                       id="maitre_stage_email_{{ $stage->id }}" 
                                       name="maitre_stage_email" 
                                       value="{{ old('maitre_stage_email', $stage->maitre_stage_email) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label for="maitre_stage_telephone_{{ $stage->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Téléphone
                                </label>
                                <input type="tel" 
                                       id="maitre_stage_telephone_{{ $stage->id }}" 
                                       name="maitre_stage_telephone" 
                                       value="{{ old('maitre_stage_telephone', $stage->maitre_stage_telephone) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3 sticky bottom-0">
                    <button type="button" 
                            onclick="closeModal('evaluer-{{ $stage->id }}')"
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
function updateNoteDisplayEntreprise(stageId, value) {
    document.getElementById('note-display-entreprise-' + stageId).textContent = value;
}

// Gestion des étoiles pour l'évaluation des compétences
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            const competence = this.dataset.competence;
            const competenceStars = document.querySelectorAll(`[data-competence="${competence}"]`);
            
            // Mettre à jour l'input radio
            const radioInput = document.querySelector(`input[name="evaluation_${competence}"][value="${rating}"]`);
            if (radioInput) {
                radioInput.checked = true;
            }
            
            // Mettre à jour l'affichage des étoiles
            competenceStars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            const competence = this.dataset.competence;
            const competenceStars = document.querySelectorAll(`[data-competence="${competence}"]`);
            
            competenceStars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                }
            });
        });
    });
});
</script>
