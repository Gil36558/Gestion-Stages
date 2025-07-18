@extends('layouts.app')

@section('title', $offre->titre)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="{{ route('offres.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux offres
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2">
                <!-- En-tête de l'offre -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $offre->titre }}</h1>
                            <div class="flex items-center text-gray-600 mb-3">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"/>
                                </svg>
                                <a href="{{ route('entreprise.show', $offre->entreprise) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $offre->entreprise->nom }}
                                </a>
                            </div>
                            @if($offre->lieu)
                                <div class="flex items-center text-gray-600 mb-3">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $offre->lieu }}
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @if($offre->canReceiveCandidatures()) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($offre->canReceiveCandidatures())
                                    ✅ Ouvert aux candidatures
                                @else
                                    ❌ Fermé aux candidatures
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-sm text-gray-500 mb-1">Date de début</div>
                            <div class="font-medium">{{ $offre->date_debut->format('d/m/Y') }}</div>
                        </div>
                        @if($offre->date_fin)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Date de fin</div>
                                <div class="font-medium">{{ $offre->date_fin->format('d/m/Y') }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Informations supplémentaires -->
                    @if($offre->duree || $offre->remuneration || $offre->niveau_etudes)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            @if($offre->duree)
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="text-sm text-blue-600 mb-1">Durée</div>
                                    <div class="font-medium text-blue-800">{{ $offre->duree }}</div>
                                </div>
                            @endif
                            @if($offre->remuneration)
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <div class="text-sm text-green-600 mb-1">Rémunération</div>
                                    <div class="font-medium text-green-800">{{ $offre->remuneration }}</div>
                                </div>
                            @endif
                            @if($offre->niveau_etudes)
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <div class="text-sm text-purple-600 mb-1">Niveau d'études</div>
                                    <div class="font-medium text-purple-800">{{ $offre->niveau_etudes }}</div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Type de stage -->
                    @if($offre->type_stage)
                        <div class="mb-4">
                            <div class="bg-indigo-50 p-3 rounded-lg inline-block">
                                <div class="text-sm text-indigo-600 mb-1">Type de stage</div>
                                <div class="font-medium text-indigo-800">{{ $offre->type_stage_display }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Description du stage</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($offre->description)) !!}
                    </div>
                </div>

                <!-- Compétences requises -->
                @if($offre->competences_requises)
                    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Compétences requises</h2>
                        <div class="text-gray-700">
                            {!! nl2br(e($offre->competences_requises)) !!}
                        </div>
                    </div>
                @endif

            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Action de candidature -->
                @auth
                    @if(auth()->user()->role === 'etudiant')
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Candidater à cette offre</h3>
                            
                            @if($offre->canReceiveCandidatures())
                                @if($candidatureExistante)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <div class="font-medium text-yellow-800">Candidature déjà envoyée</div>
                                                <div class="text-sm text-yellow-600">
                                                    Statut : {{ $candidatureExistante->statut_francais }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('candidatures.show', $candidatureExistante) }}" 
                                       class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-center block">
                                        Voir ma candidature
                                    </a>
                                @else
                                    <button onclick="openCandidatureModal()" 
                                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        📝 Postuler maintenant
                                    </button>
                                @endif
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-red-800">Candidatures fermées</div>
                                            <div class="text-sm text-red-600">Cette offre n'accepte plus de candidatures</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Candidater à cette offre</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="text-sm text-blue-800">
                                Vous devez être connecté en tant qu'étudiant pour candidater à cette offre.
                            </div>
                        </div>
                        <a href="{{ route('login') }}" 
                           class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center block">
                            Se connecter
                        </a>
                    </div>
                @endauth

                <!-- Informations sur l'entreprise -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">À propos de l'entreprise</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="text-sm text-gray-500">Nom</div>
                            <div class="font-medium">{{ $offre->entreprise->nom }}</div>
                        </div>
                        @if($offre->entreprise->secteur)
                            <div>
                                <div class="text-sm text-gray-500">Secteur</div>
                                <div class="font-medium">{{ $offre->entreprise->secteur }}</div>
                            </div>
                        @endif
                        @if($offre->entreprise->adresse)
                            <div>
                                <div class="text-sm text-gray-500">Adresse</div>
                                <div class="font-medium">{{ $offre->entreprise->adresse }}</div>
                            </div>
                        @endif
                        @if($offre->entreprise->telephone)
                            <div>
                                <div class="text-sm text-gray-500">Téléphone</div>
                                <div class="font-medium">{{ $offre->entreprise->telephone }}</div>
                            </div>
                        @endif
                        @if($offre->entreprise->site_web)
                            <div>
                                <div class="text-sm text-gray-500">Site web</div>
                                <a href="{{ $offre->entreprise->site_web }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    {{ $offre->entreprise->site_web }}
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    @if($offre->entreprise->description)
                        <div class="mt-4 pt-4 border-t">
                            <div class="text-sm text-gray-500 mb-2">Description</div>
                            <div class="text-sm text-gray-700">
                                {{ Str::limit($offre->entreprise->description, 200) }}
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('entreprise.show', $offre->entreprise) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir le profil complet →
                        </a>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Candidatures reçues</span>
                            <span class="font-medium">{{ $offre->candidatures->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Publié le</span>
                            <span class="font-medium">{{ $offre->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($offre->date_limite_candidature)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date limite</span>
                                <span class="font-medium">{{ $offre->date_limite_candidature->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de candidature -->
@auth
    @if(auth()->user()->role === 'etudiant' && $offre->canReceiveCandidatures() && !$candidatureExistante)
        @include('offres.modals.candidature', ['offre' => $offre])
    @endif
@endauth

@push('scripts')
<script>
function openCandidatureModal() {
    document.getElementById('candidature-modal').classList.remove('hidden');
}

function closeCandidatureModal() {
    document.getElementById('candidature-modal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
