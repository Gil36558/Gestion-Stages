@extends('layouts.app')

@section('title', 'Administration - Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Admin -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">👑 Administration</h1>
                    <p class="text-purple-100">Tableau de bord système - {{ auth()->user()->name }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-purple-200">Dernière connexion</div>
                    <div class="text-lg font-semibold">{{ now()->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Utilisateurs</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_utilisateurs']) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                {{ $stats['total_etudiants'] }} étudiants
                            </span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2">
                                {{ $stats['total_entreprises'] }} entreprises
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Offres Publiées</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_offres']) }}</p>
                        <p class="text-sm text-green-600 mt-2">
                            📈 {{ $statsMenuelles[5]['offres'] ?? 0 }} ce mois
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Candidatures</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_candidatures']) }}</p>
                        <p class="text-sm text-orange-600 mt-2">
                            📊 {{ $statsMenuelles[5]['candidatures'] ?? 0 }} ce mois
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Stages</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_stages']) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                                {{ $stats['stages_actifs'] }} actifs
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Graphique évolution mensuelle -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Évolution Mensuelle</h3>
                <div class="space-y-4">
                    @foreach($statsMenuelles as $mois)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $mois['mois'] }}</span>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-sm">{{ $mois['utilisateurs'] }} users</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm">{{ $mois['offres'] }} offres</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                                    <span class="text-sm">{{ $mois['stages'] }} stages</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top entreprises -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🏆 Top Entreprises</h3>
                <div class="space-y-3">
                    @foreach($topEntreprises as $index => $entreprise)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="w-6 h-6 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold rounded-full flex items-center justify-center mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $entreprise->nom }}</p>
                                    <p class="text-sm text-gray-500">{{ $entreprise->secteur ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $entreprise->offres_count }} offres
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Actions Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.utilisateurs') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Utilisateurs</p>
                        <p class="text-sm text-gray-500">Gérer les comptes</p>
                    </div>
                </a>

                <a href="{{ route('admin.offres') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Offres</p>
                        <p class="text-sm text-gray-500">Superviser les offres</p>
                    </div>
                </a>

                <a href="{{ route('admin.stages') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Stages</p>
                        <p class="text-sm text-gray-500">Suivre les stages</p>
                    </div>
                </a>

                <a href="{{ route('admin.statistiques') }}" class="flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Statistiques</p>
                        <p class="text-sm text-gray-500">Analyses avancées</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Dernières activités -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Nouveaux utilisateurs -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">👥 Nouveaux Utilisateurs</h3>
                <div class="space-y-3">
                    @foreach($dernieresActivites['utilisateurs'] as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-xs font-bold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($user->role === 'etudiant') bg-blue-100 text-blue-800
                                    @elseif($user->role === 'entreprise') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Dernières offres -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Dernières Offres</h3>
                <div class="space-y-3">
                    @foreach($dernieresActivites['offres'] as $offre)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ Str::limit($offre->titre, 30) }}</p>
                                <p class="text-sm text-gray-500">{{ $offre->entreprise->nom ?? 'Entreprise inconnue' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($offre->statut === 'active') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($offre->statut) }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $offre->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
