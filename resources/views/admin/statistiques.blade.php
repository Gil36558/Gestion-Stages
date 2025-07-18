@extends('layouts.app')

@section('title', 'Administration - Statistiques')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">📊 Statistiques Avancées</h1>
                    <p class="text-indigo-100">Analyses détaillées du système</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-colors">
                    ← Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Taux de conversion -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">🎯 Taux de Conversion</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-blue-50 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ $tauxConversion['candidatures_par_offre'] }}</div>
                    <div class="text-sm text-blue-800 font-medium">Candidatures par offre</div>
                    <div class="text-xs text-blue-600 mt-1">En moyenne</div>
                </div>
                
                <div class="text-center p-6 bg-green-50 rounded-lg">
                    <div class="text-3xl font-bold text-green-600 mb-2">{{ $tauxConversion['taux_acceptation'] }}%</div>
                    <div class="text-sm text-green-800 font-medium">Taux d'acceptation</div>
                    <div class="text-xs text-green-600 mt-1">Candidatures acceptées</div>
                </div>
                
                <div class="text-center p-6 bg-purple-50 rounded-lg">
                    <div class="text-3xl font-bold text-purple-600 mb-2">{{ $tauxConversion['stages_realises'] }}%</div>
                    <div class="text-sm text-purple-800 font-medium">Stages réalisés</div>
                    <div class="text-xs text-purple-600 mt-1">Après acceptation</div>
                </div>
            </div>
        </div>

        <!-- Évolution annuelle -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">📈 Évolution Annuelle</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Année</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-700">Utilisateurs</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-700">Offres</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-700">Candidatures</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-700">Stages</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statsAnnuelles as $annee => $stats)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium">{{ $annee }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">
                                        {{ number_format($stats['utilisateurs']) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">
                                        {{ number_format($stats['offres']) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-sm">
                                        {{ number_format($stats['candidatures']) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm">
                                        {{ number_format($stats['stages']) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Répartition géographique -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">🌍 Répartition Géographique</h3>
            
            <div class="space-y-4">
                @foreach($repartitionGeo as $index => $geo)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm font-bold rounded-full flex items-center justify-center mr-4">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">{{ $geo->adresse }}</div>
                                <div class="text-sm text-gray-500">Localisation</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">{{ $geo->total }}</div>
                            <div class="text-sm text-gray-500">entreprises</div>
                        </div>
                    </div>
                @endforeach
                
                @if($repartitionGeo->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p>Aucune donnée géographique disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions d'export -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">📥 Export des Données</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.export', ['type' => 'utilisateurs']) }}" 
                   class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <div class="font-medium text-blue-900">Utilisateurs</div>
                        <div class="text-sm text-blue-600">Export JSON</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.export', ['type' => 'offres']) }}" 
                   class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="font-medium text-green-900">Offres</div>
                        <div class="text-sm text-green-600">Export JSON</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.export', ['type' => 'stages']) }}" 
                   class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                        </svg>
                        <div class="font-medium text-purple-900">Stages</div>
                        <div class="text-sm text-purple-600">Export JSON</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
