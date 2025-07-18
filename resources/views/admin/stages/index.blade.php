@extends('layouts.app')

@section('title', 'Administration - Gestion des Stages')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">🎯 Gestion des Stages</h1>
                    <p class="text-gray-600">Superviser tous les stages en cours</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">En cours</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['en_cours']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Terminés</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['termines']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['en_attente']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <form method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher par titre, étudiant, entreprise..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <select name="statut" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente_debut" {{ request('statut') === 'en_attente_debut' ? 'selected' : '' }}>En attente</option>
                        <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    🔍 Filtrer
                </button>
                @if(request()->hasAny(['search', 'statut']))
                    <a href="{{ route('admin.stages') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        ✖️ Effacer
                    </a>
                @endif
            </form>
        </div>

        <!-- Liste des stages -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entreprise</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progression</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stages as $stage)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($stage->titre, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($stage->description, 60) }}</div>
                                        @if($stage->lieu)
                                            <div class="text-xs text-gray-400 mt-1">📍 {{ $stage->lieu }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-xs">{{ substr($stage->etudiant->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $stage->etudiant->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $stage->etudiant->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $stage->entreprise->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ $stage->entreprise->secteur ?? 'Non spécifié' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $stage->date_debut?->format('d/m/Y') ?? 'Non définie' }}</div>
                                    <div>{{ $stage->date_fin?->format('d/m/Y') ?? 'Non définie' }}</div>
                                    @if($stage->date_debut && $stage->date_fin)
                                        <div class="text-xs text-gray-400">
                                            {{ $stage->date_debut->diffInDays($stage->date_fin) }} jours
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($stage->statut === 'en_cours') bg-green-100 text-green-800
                                        @elseif($stage->statut === 'termine') bg-purple-100 text-purple-800
                                        @elseif($stage->statut === 'en_attente_debut') bg-orange-100 text-orange-800
                                        @elseif($stage->statut === 'annule') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($stage->statut === 'en_cours') 🟢 En cours
                                        @elseif($stage->statut === 'termine') 🟣 Terminé
                                        @elseif($stage->statut === 'en_attente_debut') 🟡 En attente
                                        @elseif($stage->statut === 'annule') 🔴 Annulé
                                        @else {{ ucfirst($stage->statut) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($stage->statut === 'en_cours' && $stage->date_debut && $stage->date_fin)
                                        @php
                                            $totalJours = $stage->date_debut->diffInDays($stage->date_fin);
                                            $joursEcoules = $stage->date_debut->diffInDays(now());
                                            $progression = $totalJours > 0 ? min(100, ($joursEcoules / $totalJours) * 100) : 0;
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progression }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">{{ round($progression) }}%</div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('stages.show', $stage) }}" 
                                           class="text-blue-600 hover:text-blue-900" target="_blank">
                                            👁️ Voir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                        </svg>
                                        <p class="text-lg font-medium">Aucun stage trouvé</p>
                                        <p class="text-sm">Essayez de modifier vos critères de recherche</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($stages->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $stages->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
