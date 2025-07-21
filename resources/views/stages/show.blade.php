@extends('layouts.app')

@section('title', 'Stage - ' . $stage->titre)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Navigation -->
        <div class="mb-6">
            @if(auth()->user()->estEntreprise())
                <a href="{{ route('entreprise.stages.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à mes stages
                </a>
            @else
                <a href="{{ route('stages.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à mes stages
                </a>
            @endif
        </div>

        <!-- En-tête du stage -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $stage->titre }}</h1>
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"/>
                            </svg>
                            {{ $stage->entreprise->nom }}
                        </div>
                        @if($stage->lieu)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $stage->lieu }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @switch($stage->statut)
                            @case('en_cours')
                                bg-green-100 text-green-800
                                @break
                            @case('termine')
                                bg-blue-100 text-blue-800
                                @break
                            @case('en_attente_debut')
                                bg-yellow-100 text-yellow-800
                                @break
                            @case('evalue')
                                bg-purple-100 text-purple-800
                                @break
                            @case('valide')
                                bg-green-100 text-green-800
                                @break
                            @case('annule')
                                bg-red-100 text-red-800
                                @break
                            @default
                                bg-gray-100 text-gray-800
                        @endswitch">
                        @switch($stage->statut)
                            @case('en_cours')
                                🟢 En cours
                                @break
                            @case('termine')
                                🏁 Terminé
                                @break
                            @case('en_attente_debut')
                                ⏳ En attente
                                @break
                            @case('evalue')
                                ⭐ Évalué
                                @break
                            @case('valide')
                                ✅ Validé
                                @break
                            @case('annule')
                                ❌ Annulé
                                @break
                            @default
                                {{ ucfirst($stage->statut) }}
                        @endswitch
                    </span>
                </div>
            </div>

            <!-- Progression -->
            @if($stage->statut === 'en_cours')
                @php
                    $totalDays = $stage->date_debut->diffInDays($stage->date_fin) + 1;
                    $daysPassed = $stage->date_debut->diffInDays(now()) + 1;
                    $percentage = min(100, max(0, ($daysPassed / $totalDays) * 100));
                @endphp
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Progression du stage</span>
                        <span>{{ round($percentage) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endif

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="text-gray-500 mb-1">Date de début prévue</div>
                    <div class="font-medium">{{ $stage->date_debut->format('d/m/Y') }}</div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="text-gray-500 mb-1">Date de fin prévue</div>
                    <div class="font-medium">{{ $stage->date_fin->format('d/m/Y') }}</div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="text-gray-500 mb-1">Durée</div>
                    <div class="font-medium">{{ $stage->date_debut->diffInDays($stage->date_fin) + 1 }} jours</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                @if($stage->description)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Description du stage</h2>
                        <div class="text-gray-700 whitespace-pre-line">{{ $stage->description }}</div>
                    </div>
                @endif

                <!-- Tâches réalisées -->
                @if($stage->taches_realisees)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Tâches réalisées</h2>
                        <div class="text-gray-700 whitespace-pre-line">{{ $stage->taches_realisees }}</div>
                    </div>
                @endif

                <!-- Compétences acquises -->
                @if($stage->competences_acquises)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Compétences acquises</h2>
                        <div class="text-gray-700 whitespace-pre-line">{{ $stage->competences_acquises }}</div>
                    </div>
                @endif

                <!-- Évaluations -->
                @if($stage->note_entreprise || $stage->note_etudiant)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Évaluations</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($stage->note_entreprise)
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="text-sm text-blue-600 mb-1">Note de l'entreprise</div>
                                    <div class="text-2xl font-bold text-blue-800">{{ $stage->note_entreprise }}/20</div>
                                    @if($stage->commentaire_entreprise)
                                        <div class="text-sm text-gray-600 mt-2">{{ $stage->commentaire_entreprise }}</div>
                                    @endif
                                </div>
                            @endif
                            @if($stage->note_etudiant)
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="text-sm text-green-600 mb-1">Auto-évaluation étudiant</div>
                                    <div class="text-2xl font-bold text-green-800">{{ $stage->note_etudiant }}/20</div>
                                    @if($stage->commentaire_etudiant)
                                        <div class="text-sm text-gray-600 mt-2">{{ $stage->commentaire_etudiant }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    <div class="space-y-3">
                        @if(auth()->user()->estEtudiant())
                            @if($stage->statut === 'en_attente_debut')
                                <button onclick="openModal('demarrer-{{ $stage->id }}')" 
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    🚀 Démarrer le stage
                                </button>
                            @endif

                            @if($stage->statut === 'en_cours')
                                <button onclick="openModal('terminer-{{ $stage->id }}')" 
                                        class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                                    ✅ Terminer le stage
                                </button>
                            @endif

                            @if($stage->statut === 'evalue')
                                <button onclick="openModal('auto-evaluer-{{ $stage->id }}')" 
                                        class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                                    ⭐ S'auto-évaluer
                                </button>
                            @endif
                        @endif

                        @if(auth()->user()->estEntreprise())
                            @if($stage->statut === 'en_cours')
                                <a href="{{ route('entreprise.journal.index', $stage) }}" 
                                   class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-center block">
                                    📔 Voir le journal
                                </a>
                                
                                <a href="{{ route('entreprise.journal.calendrier', $stage) }}" 
                                   class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-center block">
                                    📅 Vue calendrier
                                </a>
                            @endif

                            @if($stage->statut === 'termine' && !$stage->note_entreprise)
                                <button onclick="openModal('evaluer-{{ $stage->id }}')" 
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    ⭐ Évaluer l'étudiant
                                </button>
                            @endif

                            @if(in_array($stage->statut, ['en_attente_debut', 'en_cours']))
                                <button onclick="openModal('annuler-{{ $stage->id }}')" 
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    ❌ Annuler le stage
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Documents -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Documents</h2>
                    <div class="space-y-2">
                        @if($stage->rapport_stage)
                            <a href="{{ route('stages.download', [$stage, 'rapport']) }}" 
                               class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium">Rapport de stage</span>
                            </a>
                        @endif

                        @if($stage->attestation_stage)
                            <a href="{{ route('stages.download', [$stage, 'attestation']) }}" 
                               class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                <span class="text-sm font-medium">Attestation de stage</span>
                            </a>
                        @endif

                        @if(!$stage->rapport_stage && !$stage->attestation_stage)
                            <div class="text-sm text-gray-500 text-center py-4">
                                Aucun document disponible
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations sur la source -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Origine</h2>
                    <div class="text-sm text-gray-600">
                        @if($stage->candidature_id)
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Candidature à une offre
                            </div>
                            @if($stage->candidature && $stage->candidature->offre)
                                <div class="pl-6">
                                    <div class="font-medium">{{ $stage->candidature->offre->titre }}</div>
                                    <div class="text-xs text-gray-500">
                                        Candidature envoyée le {{ $stage->candidature->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            @endif
                        @elseif($stage->demande_stage_id)
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a1 1 0 001 1h8a1 1 0 001-1V8a1 1 0 00-1-1h-1"/>
                                </svg>
                                Demande de stage directe
                            </div>
                            @if($stage->demandeStage)
                                <div class="pl-6">
                                    <div class="text-xs text-gray-500">
                                        Demande envoyée le {{ $stage->demandeStage->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@if(auth()->user()->estEtudiant())
    @if($stage->statut === 'en_attente_debut')
        @include('stages.modals.demarrer', ['stage' => $stage])
    @endif

    @if($stage->statut === 'en_cours')
        @include('stages.modals.terminer', ['stage' => $stage])
    @endif

    @if($stage->statut === 'evalue')
        @include('stages.modals.auto-evaluer', ['stage' => $stage])
    @endif
@endif

@if(auth()->user()->estEntreprise())
    @if($stage->statut === 'termine' && !$stage->note_entreprise)
        @include('entreprise.stages.modals.evaluer', ['stage' => $stage])
    @endif

    @if(in_array($stage->statut, ['en_attente_debut', 'en_cours']))
        @include('entreprise.stages.modals.annuler', ['stage' => $stage])
    @endif
@endif

@push('scripts')
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Fermer les modals en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
