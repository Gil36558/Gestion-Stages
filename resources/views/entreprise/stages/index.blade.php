@extends('layouts.app')

@section('title', 'Gestion des Stages')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        🏢 Gestion des Stages
                    </h1>
                    <p class="text-gray-600">
                        Suivez et évaluez les stages dans votre entreprise
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $stages->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $stages->where('statut', 'en_attente_debut')->count() }}</div>
                        <div class="text-sm text-gray-500">En attente</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $stages->where('statut', 'en_cours')->count() }}</div>
                        <div class="text-sm text-gray-500">En cours</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-orange-600">{{ $stages->where('statut', 'termine')->count() }}</div>
                        <div class="text-sm text-gray-500">Terminés</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-600">{{ $stages->whereIn('statut', ['evalue', 'valide'])->count() }}</div>
                        <div class="text-sm text-gray-500">Évalués</div>
                    </div>
                </div>
            </div>
        </div>

        @if($stages->isEmpty())
            <!-- État vide -->
            <div class="bg-white rounded-lg shadow-sm border p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun stage pour le moment</h3>
                <p class="text-gray-500 mb-6">
                    Les stages apparaîtront ici lorsque vous accepterez des candidatures ou des demandes de stage.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('entreprise.demandes') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Voir les demandes
                    </a>
                    <a href="{{ route('offres.create') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        Publier une offre
                    </a>
                </div>
            </div>
        @else
            <!-- Liste des stages -->
            <div class="space-y-4">
                @foreach($stages as $stage)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $stage->titre }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            @if($stage->statut === 'en_cours') bg-blue-100 text-blue-800
                                            @elseif($stage->statut === 'termine') bg-green-100 text-green-800
                                            @elseif($stage->statut === 'en_attente_debut') bg-yellow-100 text-yellow-800
                                            @elseif($stage->statut === 'annule') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @switch($stage->statut)
                                                @case('en_cours')
                                                    En cours
                                                    @break
                                                @case('termine')
                                                    Terminé
                                                    @break
                                                @case('en_attente_debut')
                                                    En attente de début
                                                    @break
                                                @case('evalue')
                                                    Évalué
                                                    @break
                                                @case('valide')
                                                    Validé
                                                    @break
                                                @case('annule')
                                                    Annulé
                                                    @break
                                                @default
                                                    {{ ucfirst($stage->statut) }}
                                            @endswitch
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $stage->etudiant->name }}
                                        @if($stage->etudiant->email)
                                            • {{ $stage->etudiant->email }}
                                        @endif
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a1 1 0 001 1h8a1 1 0 001-1V8a1 1 0 00-1-1h-1"/>
                                        </svg>
                                        Du {{ $stage->date_debut->format('d/m/Y') }} au {{ $stage->date_fin->format('d/m/Y') }}
                                        @php
                                            $duree = $stage->date_debut->diffInDays($stage->date_fin) + 1;
                                        @endphp
                                        <span class="ml-2 text-gray-400">
                                            ({{ $duree }} jours)
                                        </span>
                                    </div>

                                    @if($stage->description)
                                        <p class="text-sm text-gray-600 mb-3">
                                            {{ Str::limit($stage->description, 150) }}
                                        </p>
                                    @endif

                                    <!-- Source du stage -->
                                    <div class="text-xs text-gray-500">
                                        @if($stage->candidature_id)
                                            📝 Issu d'une candidature à une offre
                                        @elseif($stage->demande_stage_id)
                                            📋 Issu d'une demande de stage directe
                                        @else
                                            📄 Stage créé directement
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <a href="{{ route('stages.show', $stage) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        Voir détails
                                    </a>
                                </div>
                            </div>

                            <!-- Actions rapides -->
                            <div class="flex gap-2 pt-4 border-t">
                                @if($stage->statut === 'en_cours')
                                    <a href="{{ route('journal.index', $stage) }}" 
                                       class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition-colors">
                                        📔 Journal
                                    </a>
                                @endif

                                @if($stage->rapport_stage)
                                    <a href="{{ route('stages.download', [$stage, 'rapport']) }}" 
                                       class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 transition-colors">
                                        📄 Rapport
                                    </a>
                                @endif

                                @if($stage->attestation_stage)
                                    <a href="{{ route('stages.download', [$stage, 'attestation']) }}" 
                                       class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 transition-colors">
                                        🏆 Attestation
                                    </a>
                                @endif

                                @if($stage->note_entreprise)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm">
                                        ✅ Évalué ({{ $stage->note_entreprise }}/20)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($stages->hasPages())
                <div class="mt-6">
                    {{ $stages->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
