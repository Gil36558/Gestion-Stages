@extends('layouts.app')

@section('title', 'Calendrier du journal de stage')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Bouton retour -->
        <div class="mb-6">
            <a href="{{ route('entreprise.journal.index', $stage) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour au journal
            </a>
        </div>

        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Calendrier du journal de stage
                </h1>
                <p class="text-gray-600">
                    <strong>{{ $stage->etudiant->name }}</strong> - {{ $stage->titre }}
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Période : {{ $stage->date_debut->format('d/m/Y') }} - {{ $stage->date_fin->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 shadow-sm border text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $entrees->count() }}</div>
                <div class="text-sm text-gray-600">Total entrées</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border text-center">
                <div class="text-2xl font-bold text-green-600">{{ $entrees->where('statut', 'valide')->count() }}</div>
                <div class="text-sm text-gray-600">Validées</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $entrees->where('statut', 'soumis')->count() }}</div>
                <div class="text-sm text-gray-600">En attente</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border text-center">
                <div class="text-2xl font-bold text-red-600">{{ $entrees->where('statut', 'rejete')->count() }}</div>
                <div class="text-sm text-gray-600">Rejetées</div>
            </div>
        </div>

        <!-- Navigation du calendrier -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <div class="flex justify-between items-center">
                <button onclick="changeMonth(-1)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-chevron-left mr-2"></i> Mois précédent
                </button>
                
                <div class="text-xl font-semibold text-gray-800" id="current-month-year">
                    {{ now()->format('F Y') }}
                </div>
                
                <button onclick="changeMonth(1)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Mois suivant <i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Calendrier simple -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="grid grid-cols-7 gap-2 mb-4">
                <div class="text-center font-semibold text-gray-700 p-2">Lun</div>
                <div class="text-center font-semibold text-gray-700 p-2">Mar</div>
                <div class="text-center font-semibold text-gray-700 p-2">Mer</div>
                <div class="text-center font-semibold text-gray-700 p-2">Jeu</div>
                <div class="text-center font-semibold text-gray-700 p-2">Ven</div>
                <div class="text-center font-semibold text-gray-700 p-2">Sam</div>
                <div class="text-center font-semibold text-gray-700 p-2">Dim</div>
            </div>

            <div class="grid grid-cols-7 gap-2" id="calendar-grid">
                @php
                    $currentMonth = now();
                    $startOfMonth = $currentMonth->copy()->startOfMonth();
                    $endOfMonth = $currentMonth->copy()->endOfMonth();
                    $startOfCalendar = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                    $endOfCalendar = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
                    
                    $current = $startOfCalendar->copy();
                    $today = \Carbon\Carbon::today();
                @endphp

                @while($current <= $endOfCalendar)
                    @php
                        $isCurrentMonth = $current->month === $currentMonth->month;
                        $isToday = $current->isSameDay($today);
                        $dateKey = $current->format('Y-m-d');
                        $dayEntry = $entrees->get($dateKey);
                    @endphp
                    
                    <div class="border rounded p-2 h-20 {{ $isToday ? 'bg-blue-50 border-blue-300' : 'border-gray-200' }} {{ !$isCurrentMonth ? 'opacity-50' : '' }}">
                        <div class="font-semibold text-sm {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                            {{ $current->day }}
                        </div>
                        
                        @if($dayEntry)
                            <div class="mt-1">
                                <div class="text-xs px-2 py-1 rounded 
                                    @if($dayEntry->statut === 'valide') bg-green-100 text-green-800
                                    @elseif($dayEntry->statut === 'soumis') bg-yellow-100 text-yellow-800
                                    @elseif($dayEntry->statut === 'rejete') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @switch($dayEntry->statut)
                                        @case('valide')
                                            ✅ Validé
                                            @break
                                        @case('soumis')
                                            ⏳ En attente
                                            @break
                                        @case('rejete')
                                            ❌ Rejeté
                                            @break
                                        @default
                                            📝 Brouillon
                                    @endswitch
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @php $current->addDay(); @endphp
                @endwhile
            </div>

            <!-- Légende -->
            <div class="flex justify-center gap-6 mt-6 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border border-green-300 rounded mr-2"></div>
                    <span>Entrée validée</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded mr-2"></div>
                    <span>En attente de validation</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-100 border border-red-300 rounded mr-2"></div>
                    <span>Entrée rejetée</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-50 border-2 border-blue-300 rounded mr-2"></div>
                    <span>Aujourd'hui</span>
                </div>
            </div>
        </div>

        <!-- Liste des entrées récentes -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Entrées récentes</h3>
            
            @if($entrees->count() > 0)
                <div class="space-y-3">
                    @foreach($entrees->sortByDesc('date_activite')->take(5) as $entree)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">
                                    {{ $entree->date_activite->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ Str::limit($entree->taches_effectuees, 100) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($entree->statut === 'valide') bg-green-100 text-green-800
                                    @elseif($entree->statut === 'soumis') bg-yellow-100 text-yellow-800
                                    @elseif($entree->statut === 'rejete') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($entree->statut) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-calendar-times text-4xl mb-4 opacity-50"></i>
                    <p>Aucune entrée de journal pour le moment</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentDate = new Date();

    function changeMonth(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        
        // Mettre à jour l'affichage du mois
        const monthNames = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];
        
        document.getElementById('current-month-year').textContent = 
            monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
        
        // Ici, vous pourriez recharger le calendrier avec AJAX
        // Pour l'instant, on recharge la page
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        
        window.location.href = `{{ route('entreprise.journal.calendrier', $stage) }}?month=${month}&year=${year}`;
    }
</script>
@endpush
