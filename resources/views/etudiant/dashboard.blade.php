@extends('layouts.app')

@section('title', 'Tableau de bord étudiant')

@push('styles')
<!-- AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<!-- Tailwind CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-blue: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #3b82f6;
        --secondary-blue: #eff6ff;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --background: #ffffff;
        --background-alt: #f8fafc;
        --border-color: #e5e7eb;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .dashboard-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
        display: flex;
        justify-content: center;
    }

    .dashboard-card {
        background: var(--background);
        border-radius: 1.5rem;
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
        max-width: 1200px;
        padding: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-primary);
        position: relative;
    }

    .dashboard-header h1::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: var(--primary-blue);
        border-radius: 2px;
    }

    .dashboard-header p {
        font-size: 1.1rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--background-alt);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease, background 0.3s ease;
    }

    .stat-card:hover {
        background: var(--secondary-blue);
        transform: translateY(-5px);
    }

    .stat-card p {
        font-size: 0.95rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .stat-card h2 {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--primary-blue);
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border-radius: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        gap: 0.5rem;
    }

    .action-btn.primary {
        background: var(--primary-blue);
        color: white;
    }

    .action-btn.primary:hover {
        background: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        color: white;
    }

    .action-btn.secondary {
        background: var(--background-alt);
        color: var(--text-primary);
    }

    .action-btn.secondary:hover {
        background: var(--secondary-blue);
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
        color: var(--text-primary);
    }

    .candidatures-card {
        background: var(--background);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }

    .candidatures-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    .candidature-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .candidature-item:last-child {
        border-bottom: none;
    }

    .candidature-info p {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .candidature-info span {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .candidature-status {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .candidature-status.acceptee {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .candidature-status.refusee {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
    }

    .candidature-status.en_attente {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .card-grid,
        .action-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header h1 {
            font-size: 1.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container" data-aos="fade-up" data-aos-duration="800">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <h1>Bonjour, {{ Auth::user()->prenom }} {{ Auth::user()->name }} 👋</h1>
            <p>Gérez vos candidatures, demandes de stage et découvrez de nouvelles opportunités.</p>
        </div>

        {{-- Statistiques principales --}}
        <div class="card-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <p>Candidatures aux offres</p>
                <h2>{{ $stats['candidatures']['total'] ?? 0 }}</h2>
            </div>
            <div class="stat-card">
                <p>Demandes de stage</p>
                <h2>{{ $stats['demandes']['total'] ?? 0 }}</h2>
            </div>
            <div class="stat-card">
                <p>En attente</p>
                <h2>{{ ($stats['candidatures']['en_attente'] ?? 0) + ($stats['demandes']['en_attente'] ?? 0) }}</h2>
            </div>
            <div class="stat-card">
                <p>Acceptées/Validées</p>
                <h2>{{ ($stats['candidatures']['acceptees'] ?? 0) + ($stats['demandes']['validees'] ?? 0) }}</h2>
            </div>
        </div>

        {{-- Statistiques détaillées --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" data-aos="fade-up" data-aos-delay="150">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-3">📋 Candidatures aux offres</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Total envoyées:</span>
                        <span class="font-medium">{{ $stats['candidatures']['total'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>En attente:</span>
                        <span class="font-medium text-yellow-600">{{ $stats['candidatures']['en_attente'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Acceptées:</span>
                        <span class="font-medium text-green-600">{{ $stats['candidatures']['acceptees'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Refusées:</span>
                        <span class="font-medium text-red-600">{{ $stats['candidatures']['refusees'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-semibold text-green-800 mb-3">🎓 Demandes de stage</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Total envoyées:</span>
                        <span class="font-medium">{{ $stats['demandes']['total'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>En attente:</span>
                        <span class="font-medium text-yellow-600">{{ $stats['demandes']['en_attente'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Validées:</span>
                        <span class="font-medium text-green-600">{{ $stats['demandes']['validees'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Refusées:</span>
                        <span class="font-medium text-red-600">{{ $stats['demandes']['refusees'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="action-grid" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('stages.index') }}" class="action-btn primary">
                <i class="fas fa-briefcase"></i> Mes Stages
            </a>
            <a href="{{ route('offres.index') }}" class="action-btn primary">
                <i class="fas fa-search"></i> Voir les offres
            </a>
            <a href="{{ route('entreprise.index') }}" class="action-btn secondary">
                <i class="fas fa-building"></i> Faire une demande
            </a>
            <a href="{{ route('candidatures.index') }}" class="action-btn secondary">
                <i class="fas fa-list"></i> Mes candidatures
            </a>
        </div>

        {{-- Mes Stages --}}
        <div class="candidatures-card" data-aos="fade-up" data-aos-delay="250">
            <h3>Mes Stages</h3>
            <div class="divide-y divide-gray-200">
                @forelse($mesStages ?? [] as $stage)
                    @php
                        $statusClass = match(strtolower($stage->statut)) {
                            'en_cours' => 'acceptee',
                            'termine' => 'acceptee',
                            'evalue' => 'acceptee',
                            'valide' => 'acceptee',
                            'en_attente_debut' => 'en_attente',
                            'annule' => 'refusee',
                            default => 'en_attente',
                        };
                    @endphp
                    <div class="candidature-item">
                        <div class="candidature-info">
                            <p>{{ $stage->titre }}</p>
                            <span>@ {{ $stage->entreprise->nom ?? 'Entreprise inconnue' }} • Du {{ $stage->date_debut->format('d/m/Y') }} au {{ $stage->date_fin->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="candidature-status {{ $statusClass }}">
                                {{ $stage->statut_francais }}
                            </span>
                            @if($stage->statut === 'en_cours')
                                <a href="{{ route('journal.index', $stage) }}" 
                                   class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition-colors">
                                    📔 Journal
                                </a>
                            @endif
                            <a href="{{ route('stages.show', $stage) }}" 
                               class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                Voir
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Aucun stage trouvé. Candidatez aux offres ou faites une demande directe !</p>
                        <div class="mt-4 space-x-4">
                            <a href="{{ route('offres.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir les offres →
                            </a>
                            <a href="{{ route('entreprise.index') }}" class="text-green-600 hover:text-green-800 font-medium">
                                Faire une demande →
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
            @if(($mesStages ?? collect())->count() > 0)
                <div class="text-center mt-4">
                    <a href="{{ route('stages.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Voir tous mes stages →
                    </a>
                </div>
            @endif
        </div>

        {{-- Candidatures récentes --}}
        <div class="candidatures-card" data-aos="fade-up" data-aos-delay="300">
            <h3>Candidatures récentes</h3>
            <div class="divide-y divide-gray-200">
                @forelse($recentCandidatures as $candidature)
                    @php
                        $statusClass = match(strtolower($candidature->statut)) {
                            'acceptée' => 'acceptee',
                            'refusée' => 'refusee',
                            'en attente' => 'en_attente',
                            default => 'en_attente',
                        };
                    @endphp
                    <div class="candidature-item">
                        <div class="candidature-info">
                            <p>{{ $candidature->offre->titre ?? 'Offre inconnue' }}</p>
                            <span>@ {{ $candidature->offre->entreprise->nom ?? 'Entreprise inconnue' }} • {{ $candidature->created_at->format('d/m/Y') }}</span>
                        </div>
                        <span class="candidature-status {{ $statusClass }}">
                            {{ ucfirst($candidature->statut) }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Aucune candidature récente trouvée.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Demandes de stage récentes --}}
        <div class="candidatures-card" data-aos="fade-up" data-aos-delay="400">
            <h3>Demandes de stage récentes</h3>
            <div class="divide-y divide-gray-200">
                @forelse($recentDemandes as $demande)
                    @php
                        $statusClass = match(strtolower($demande->statut)) {
                            'validée' => 'acceptee',
                            'refusée' => 'refusee',
                            'en attente' => 'en_attente',
                            default => 'en_attente',
                        };
                    @endphp
                    <div class="candidature-item">
                        <div class="candidature-info">
                            <p>{{ $demande->objet ?? 'Stage ' . ucfirst($demande->type) }}</p>
                            <span>@ {{ $demande->entreprise->nom ?? 'Entreprise inconnue' }} • {{ $demande->created_at->format('d/m/Y') }}</span>
                        </div>
                        <span class="candidature-status {{ $statusClass }}">
                            {{ ucfirst($demande->statut) }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Aucune demande de stage récente trouvée.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Offres disponibles pour candidater --}}
        <div class="candidatures-card" data-aos="fade-up" data-aos-delay="500">
            <h3>Offres disponibles pour candidater</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($offresDisponibles as $offre)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-blue-50 transition-colors">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ $offre->titre }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $offre->entreprise->nom ?? 'Entreprise inconnue' }}</p>
                        <p class="text-sm text-gray-500 mb-3">{{ Str::limit($offre->description, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">{{ $offre->lieu ?? 'Lieu non spécifié' }}</span>
                            <a href="{{ route('offres.show', $offre) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir détails →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p>Aucune offre disponible pour le moment.</p>
                    </div>
                @endforelse
            </div>
            @if($offresDisponibles->count() > 0)
                <div class="text-center mt-4">
                    <a href="{{ route('offres.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Voir toutes les offres →
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        offset: 100,
        duration: 800,
        easing: 'ease-out-cubic',
    });
</script>
@endpush
