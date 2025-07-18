@extends('layouts.app')

@section('title', 'Mes Stages')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        🎯 Mes Stages
                    </h1>
                    <p class="text-gray-600">
                        Suivez l'avancement de vos stages et gérez vos documents
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $stages->count() }}</div>
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
                    Vos stages apparaîtront ici une fois que vos candidatures ou demandes seront acceptées.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('offres.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Voir les offres
                    </a>
                    <a href="{{ route('entreprise.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        Faire une demande
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"/>
                                        </svg>
                                        {{ $stage->entreprise->nom }}
                                        @if($stage->lieu)
                                            • {{ $stage->lieu }}
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
                                        ⭐ Note: {{ $stage->note_entreprise }}/20
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
