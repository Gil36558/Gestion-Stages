@extends('layouts.app')

@section('title', 'Journal de Stage')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête avec informations du stage -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <h1 class="text-2xl font-bold text-gray-900">
                            📔 Journal de Stage
                        </h1>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            @if($stage->statut_couleur === 'success') bg-green-100 text-green-800
                            @elseif($stage->statut_couleur === 'warning') bg-yellow-100 text-yellow-800
                            @elseif($stage->statut_couleur === 'info') bg-blue-100 text-blue-800
                            @elseif($stage->statut_couleur === 'danger') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $stage->statut_francais }}
                        </span>
                    </div>
                    
                    <div class="text-gray-600 mb-2">
                        <strong>{{ $stage->titre }}</strong> chez {{ $stage->entreprise->nom }}
                    </div>
                    
                    <div class="text-sm text-gray-500">
                        Du {{ $stage->date_debut->format('d/m/Y') }} au {{ $stage->date_fin->format('d/m/Y') }}
                        @if(Auth::user()->role === 'entreprise')
                            • Étudiant : {{ $stage->etudiant->name }}
                        @endif
                    </div>
                </div>
                
                <div class="flex gap-3">
                    @if(Auth::user()->role === 'etudiant' && $stage->statut === 'en_cours')
                        <a href="{{ route('journal.create', $stage) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            ✏️ Nouvelle entrée
                        </a>
                    @endif
                    
                    @if(Auth::user()->role === 'entreprise')
                        <a href="{{ route('entreprise.journal.calendrier', $stage) }}" 
                           class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            📅 Vue calendrier
                        </a>
                    @endif
                    
                    <a href="{{ route('stages.show', $stage) }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        ← Retour au stage
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques du journal -->
        @if($stats['total_entrees'] > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_entrees'] }}</div>
                            <div class="text-sm text-gray-500">Entrées totales</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ $stats['entrees_validees'] }}</div>
                            <div class="text-sm text-gray-500">Validées</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-orange-600">{{ $stats['moyenne_heures'] ? round($stats['moyenne_heures'], 1) : 0 }}h</div>
                            <div class="text-sm text-gray-500">Moy. heures/jour</div>
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
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['moyenne_note'] ? round($stats['moyenne_note'], 1) : '-' }}</div>
                            <div class="text-sm text-gray-500">Note moyenne</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($entrees->isEmpty())
            <!-- État vide -->
            <div class="bg-white rounded-lg shadow-sm border p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune entrée de journal</h3>
                <p class="text-gray-500 mb-6">
                    @if(Auth::user()->role === 'etudiant')
                        Commencez à documenter votre stage en créant votre première entrée de journal.
                    @else
                        L'étudiant n'a pas encore commencé son journal de stage.
                    @endif
                </p>
                @if(Auth::user()->role === 'etudiant' && $stage->statut === 'en_cours')
                    <a href="{{ route('journal.create', $stage) }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        ✏️ Créer ma première entrée
                    </a>
                @endif
            </div>
        @else
            <!-- Liste des entrées -->
            <div class="space-y-4">
                @foreach($entrees as $entree)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $entree->date_activite->format('d/m/Y') }} - {{ $entree->jour_semaine }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            @if($entree->statut_couleur === 'green') bg-green-100 text-green-800
                                            @elseif($entree->statut_couleur === 'blue') bg-blue-100 text-blue-800
                                            @elseif($entree->statut_couleur === 'red') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $entree->statut_francais }}
                                        </span>
                                        @if($entree->note_journee)
                                            <span class="text-sm text-yellow-600">
                                                {{ $entree->note_etoiles }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="text-gray-600 mb-3">
                                        {{ Str::limit($entree->taches_effectuees, 150) }}
                                    </div>
                                    
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        @if($entree->heures_travaillees)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $entree->heures_travaillees }}h
                                            </span>
                                        @endif
                                        
                                        @if($entree->nombre_fichiers > 0)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                {{ $entree->nombre_fichiers }} fichier(s)
                                            </span>
                                        @endif
                                        
                                        @if($entree->estCommentee())
                                            <span class="flex items-center gap-1 text-green-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.436L3 21l1.436-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                                                </svg>
                                                Commentée
                                            </span>
                                        @endif
                                        
                                        @if($entree->estRecente())
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                Nouveau
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-end gap-2">
                                    @if(Auth::user()->role === 'entreprise')
                                        <a href="{{ route('entreprise.journal.show', [$stage, $entree]) }}" 
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                            Voir détails
                                        </a>
                                    @else
                                        <a href="{{ route('journal.show', [$stage, $entree]) }}" 
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                            Voir détails
                                        </a>
                                    @endif
                                    
                                    @if(Auth::user()->role === 'etudiant' && $entree->peutEtreModifiee())
                                        <a href="{{ route('journal.edit', [$stage, $entree]) }}" 
                                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                            Modifier
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $entrees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
