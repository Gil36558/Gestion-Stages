@extends('layouts.app')

@section('title', 'Entrée de Journal')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        📔 Entrée du {{ $journal->date_activite->format('d/m/Y') }}
                    </h1>
                    <p class="text-gray-600">
                        Stage : <strong>{{ $stage->titre }}</strong> chez {{ $stage->entreprise->nom }}
                        @if(Auth::user()->role === 'entreprise')
                            • Étudiant : {{ $stage->etudiant->name }}
                        @endif
                    </p>
                </div>
                <div class="flex gap-3">
                    @if(Auth::user()->role === 'etudiant' && $journal->peutEtreModifiee())
                        <a href="{{ route('journal.edit', [$stage, $journal]) }}" 
                           class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                            ✏️ Modifier
                        </a>
                    @endif
                    
                    @if(Auth::user()->role === 'entreprise')
                        <a href="{{ route('entreprise.journal.index', $stage) }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            ← Retour au journal
                        </a>
                    @else
                        <a href="{{ route('journal.index', $stage) }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            ← Retour au journal
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a1 1 0 001 1h8a1 1 0 001-1V8a1 1 0 00-1-1h-1"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Date</div>
                        <div class="font-semibold">{{ $journal->date_activite->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $journal->jour_semaine }}</div>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Statut</div>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            @if($journal->statut_couleur === 'green') bg-green-100 text-green-800
                            @elseif($journal->statut_couleur === 'blue') bg-blue-100 text-blue-800
                            @elseif($journal->statut_couleur === 'red') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $journal->statut_francais }}
                        </span>
                    </div>
                </div>

                @if($journal->heures_travaillees)
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Heures travaillées</div>
                            <div class="font-semibold">{{ $journal->heures_travaillees }}h</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contenu de l'entrée -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">📝 Tâches effectuées</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($journal->taches_effectuees)) !!}
            </div>
        </div>

        @if($journal->observations)
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">💭 Observations et réflexions</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($journal->observations)) !!}
                </div>
            </div>
        @endif

        @if($journal->difficultes_rencontrees)
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">⚠️ Difficultés rencontrées</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($journal->difficultes_rencontrees)) !!}
                </div>
            </div>
        @endif

        @if($journal->apprentissages)
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">🎓 Apprentissages et compétences</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($journal->apprentissages)) !!}
                </div>
            </div>
        @endif

        <!-- Fichiers joints -->
        @if($journal->fichiers_joints && count($journal->fichiers_joints) > 0)
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">📎 Fichiers joints</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($journal->fichiers_joints as $index => $fichier)
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                @if(in_array(pathinfo($fichier['nom_original'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-900 truncate">{{ $fichier['nom_original'] }}</div>
                                <div class="text-sm text-gray-500">{{ number_format($fichier['taille'] / 1024, 1) }} KB</div>
                            </div>
                            @if(Auth::user()->role === 'entreprise')
                                <a href="{{ route('entreprise.journal.fichier', [$stage, $journal, $index]) }}" 
                                   class="ml-3 bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                    Télécharger
                                </a>
                            @else
                                <a href="{{ route('journal.fichier', [$stage, $journal, $index]) }}" 
                                   class="ml-3 bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                    Télécharger
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Commentaire de l'entreprise -->
        @if($journal->estCommentee())
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="flex items-start justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">💬 Commentaire de l'entreprise</h2>
                    @if($journal->note_journee)
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Note :</span>
                            <span class="text-lg">{{ $journal->note_etoiles }}</span>
                            <span class="text-sm text-gray-500">({{ $journal->note_journee }}/5)</span>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-3">
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($journal->commentaire_entreprise)) !!}
                    </div>
                </div>
                
                <div class="text-sm text-gray-500">
                    Commenté par {{ $journal->commentateur->name ?? 'Entreprise' }} 
                    le {{ $journal->date_commentaire_entreprise->format('d/m/Y à H:i') }}
                </div>
            </div>
        @endif

        <!-- Actions pour l'étudiant -->
        @if(Auth::user()->role === 'etudiant')
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="flex gap-3">
                    @if($journal->peutEtreSoumise())
                        <form action="{{ route('journal.soumettre', [$stage, $journal]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir soumettre cette entrée ? Vous ne pourrez plus la modifier après soumission.')"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                📤 Soumettre pour validation
                            </button>
                        </form>
                    @endif
                    
                    @if($journal->peutEtreModifiee())
                        <a href="{{ route('journal.edit', [$stage, $journal]) }}" 
                           class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                            ✏️ Modifier
                        </a>
                    @endif
                    
                    @if($journal->statut === 'brouillon')
                        <form action="{{ route('journal.destroy', [$stage, $journal]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée ?')"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                🗑️ Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <!-- Formulaire de commentaire pour l'entreprise -->
        @if(Auth::user()->role === 'entreprise' && $journal->peutEtreCommentee())
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">💬 Ajouter un commentaire</h2>
                
                <form action="{{ route('entreprise.journal.commenter', [$stage, $journal]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="commentaire_entreprise" class="block text-sm font-medium text-gray-700 mb-2">
                            Votre commentaire <span class="text-red-500">*</span>
                        </label>
                        <textarea id="commentaire_entreprise" 
                                  name="commentaire_entreprise" 
                                  rows="4"
                                  placeholder="Votre retour sur cette journée de stage..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  required>{{ old('commentaire_entreprise') }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="note_journee" class="block text-sm font-medium text-gray-700 mb-2">
                            Note de la journée (optionnel)
                        </label>
                        <select id="note_journee" 
                                name="note_journee" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pas de note</option>
                            <option value="1" {{ old('note_journee') == '1' ? 'selected' : '' }}>⭐ (1/5) - Insuffisant</option>
                            <option value="2" {{ old('note_journee') == '2' ? 'selected' : '' }}>⭐⭐ (2/5) - Passable</option>
                            <option value="3" {{ old('note_journee') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3/5) - Bien</option>
                            <option value="4" {{ old('note_journee') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4/5) - Très bien</option>
                            <option value="5" {{ old('note_journee') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5/5) - Excellent</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Décision <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="statut" 
                                       value="valide" 
                                       {{ old('statut', 'valide') === 'valide' ? 'checked' : '' }}
                                       class="mr-3 text-green-600 focus:ring-green-500">
                                <span class="text-green-700 font-medium">✅ Valider cette entrée</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="statut" 
                                       value="rejete" 
                                       {{ old('statut') === 'rejete' ? 'checked' : '' }}
                                       class="mr-3 text-red-600 focus:ring-red-500">
                                <span class="text-red-700 font-medium">❌ Rejeter cette entrée</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            💬 Envoyer le commentaire
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
