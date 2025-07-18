@extends('layouts.app')

@section('title', 'Candidature - ' . $candidature->offre->titre)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Navigation -->
        <div class="mb-6">
            @if(auth()->user()->role === 'etudiant')
                <a href="{{ route('candidatures.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à mes candidatures
                </a>
            @else
                <a href="{{ route('entreprise.candidatures') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour aux candidatures reçues
                </a>
            @endif
        </div>

        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        Candidature pour : {{ $candidature->offre->titre }}
                    </h1>
                    <div class="flex items-center text-gray-600 mb-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"/>
                        </svg>
                        <span class="font-medium">{{ $candidature->offre->entreprise->nom }}</span>
                    </div>
                    @if(auth()->user()->role === 'entreprise')
                        <div class="flex items-center text-gray-600 mb-3">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-medium">{{ $candidature->user->name }}</span>
                            @if($candidature->user->email)
                                <span class="text-sm text-gray-500 ml-2">({{ $candidature->user->email }})</span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if($candidature->statut === 'en attente') bg-yellow-100 text-yellow-800
                        @elseif($candidature->statut === 'acceptée') bg-green-100 text-green-800
                        @elseif($candidature->statut === 'refusée') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $candidature->statut_francais }}
                    </span>
                    <div class="text-sm text-gray-500 mt-1">
                        Envoyée le {{ $candidature->created_at->format('d/m/Y à H:i') }}
                    </div>
                    @if($candidature->date_reponse)
                        <div class="text-sm text-gray-500">
                            Réponse le {{ $candidature->date_reponse->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Message de motivation -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Message de motivation</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($candidature->message)) !!}
                    </div>
                </div>

                <!-- Informations complémentaires -->
                @if($candidature->informations_complementaires)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations complémentaires</h2>
                        <div class="text-gray-700">
                            {!! nl2br(e($candidature->informations_complementaires)) !!}
                        </div>
                    </div>
                @endif

                <!-- Compétences -->
                @if($candidature->competences)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Compétences</h2>
                        <div class="text-gray-700">
                            {!! nl2br(e($candidature->competences)) !!}
                        </div>
                    </div>
                @endif

                <!-- Expériences -->
                @if($candidature->experiences)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Expériences pertinentes</h2>
                        <div class="text-gray-700">
                            {!! nl2br(e($candidature->experiences)) !!}
                        </div>
                    </div>
                @endif

                <!-- Motif de refus -->
                @if($candidature->statut === 'refusée' && $candidature->motif_refus)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-red-900 mb-4">Motif du refus</h2>
                        <div class="text-red-800">
                            {!! nl2br(e($candidature->motif_refus)) !!}
                        </div>
                    </div>
                @endif

                <!-- Actions pour l'entreprise -->
                @if(auth()->user()->role === 'entreprise' && $candidature->statut === 'en attente')
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                        <div class="flex space-x-4">
                            <form action="{{ route('candidatures.approve', $candidature) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir accepter cette candidature ? Un stage sera créé automatiquement.')">
                                    ✅ Accepter la candidature
                                </button>
                            </form>
                            
                            <button onclick="openRejectModal()" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                ❌ Refuser la candidature
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Actions pour l'étudiant -->
                @if(auth()->user()->role === 'etudiant' && $candidature->canBeCancelled())
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                        <form action="{{ route('candidatures.cancel', $candidature) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors"
                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette candidature ?')">
                                🚫 Annuler ma candidature
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Informations sur l'offre -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails de l'offre</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="text-sm text-gray-500">Poste</div>
                            <div class="font-medium">{{ $candidature->offre->titre }}</div>
                        </div>
                        @if($candidature->offre->lieu)
                            <div>
                                <div class="text-sm text-gray-500">Lieu</div>
                                <div class="font-medium">{{ $candidature->offre->lieu }}</div>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm text-gray-500">Période</div>
                            <div class="font-medium">
                                Du {{ $candidature->offre->date_debut->format('d/m/Y') }}
                                @if($candidature->offre->date_fin) au {{ $candidature->offre->date_fin->format('d/m/Y') }} @endif
                            </div>
                        </div>
                        @if($candidature->offre->duree)
                            <div>
                                <div class="text-sm text-gray-500">Durée</div>
                                <div class="font-medium">{{ $candidature->offre->duree }}</div>
                            </div>
                        @endif
                        @if($candidature->offre->remuneration)
                            <div>
                                <div class="text-sm text-gray-500">Rémunération</div>
                                <div class="font-medium">{{ $candidature->offre->remuneration }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('offres.show', $candidature->offre) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir l'offre complète →
                        </a>
                    </div>
                </div>

                <!-- Disponibilités du candidat -->
                @if($candidature->date_debut_disponible || $candidature->duree_souhaitee)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Disponibilités</h3>
                        <div class="space-y-3">
                            @if($candidature->date_debut_disponible)
                                <div>
                                    <div class="text-sm text-gray-500">Date de début souhaitée</div>
                                    <div class="font-medium">{{ $candidature->date_debut_disponible->format('d/m/Y') }}</div>
                                </div>
                            @endif
                            @if($candidature->duree_souhaitee)
                                <div>
                                    <div class="text-sm text-gray-500">Durée souhaitée</div>
                                    <div class="font-medium">{{ $candidature->duree_souhaitee }} semaines</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Documents -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents</h3>
                    <div class="space-y-3">
                        @if($candidature->hasCv())
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">CV</span>
                                <a href="{{ route('candidatures.download', [$candidature, 'cv']) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    📄 Télécharger
                                </a>
                            </div>
                        @endif
                        @if($candidature->hasLettre())
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Lettre de motivation</span>
                                <a href="{{ route('candidatures.download', [$candidature, 'lettre']) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    📄 Télécharger
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stage créé (si candidature acceptée) -->
                @if($candidature->statut === 'acceptée' && $candidature->stage)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Stage créé</h3>
                        <div class="text-sm text-green-800 mb-3">
                            Un stage a été automatiquement créé suite à l'acceptation de cette candidature.
                        </div>
                        <a href="{{ route('stages.show', $candidature->stage) }}" 
                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Voir le stage →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de refus (pour les entreprises) -->
@if(auth()->user()->role === 'entreprise' && $candidature->statut === 'en attente')
    <div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <form action="{{ route('candidatures.reject', $candidature) }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Refuser la candidature</h3>
                    </div>
                    <div class="px-6 py-4">
                        <label for="motif_refus" class="block text-sm font-medium text-gray-700 mb-2">
                            Motif du refus <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motif_refus" 
                                  name="motif_refus" 
                                  rows="4"
                                  placeholder="Expliquez brièvement pourquoi vous refusez cette candidature..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  required></textarea>
                    </div>
                    <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeRejectModal()"
                                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Refuser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
function openRejectModal() {
    document.getElementById('reject-modal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
}
</script>
@endpush
@endsection
