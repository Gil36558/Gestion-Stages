@extends('layouts.app')

@section('title', 'Tableau de bord entreprise')

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

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
        border-left: 4px solid;
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border-color: var(--warning-color);
        color: #92400e;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border-color: var(--success-color);
        color: #065f46;
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
    }

    .action-btn.secondary {
        background: var(--background-alt);
        color: var(--text-primary);
    }

    .action-btn.secondary:hover {
        background: var(--secondary-blue);
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
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
            <h1>Tableau de bord Entreprise 🏢</h1>
            <p>Gérez vos offres de stage et vos candidatures reçues.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" data-aos="fade-in">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(!$entreprise)
            {{-- Profil entreprise non créé --}}
            <div class="alert alert-warning" data-aos="fade-in">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Profil incomplet :</strong> Veuillez compléter les informations de votre entreprise pour commencer à publier des offres.
            </div>

            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('entreprise.create') }}" class="action-btn primary">
                    <i class="fas fa-plus"></i> Créer le profil entreprise
                </a>
            </div>
        @else
            {{-- Profil entreprise existant --}}
            @if(isset($profilIncomplet) && $profilIncomplet)
                <div class="alert alert-warning" data-aos="fade-in">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Profil à compléter :</strong> Ajoutez plus d'informations (secteur, description, site web) pour améliorer votre visibilité.
                </div>
            @endif

            {{-- Statistiques principales --}}
            <div class="card-grid" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <p>Offres publiées</p>
                    <h2>{{ $stats['offres'] ?? 0 }}</h2>
                </div>
                <div class="stat-card">
                    <p>Candidatures reçues</p>
                    <h2>{{ $stats['candidatures'] ?? 0 }}</h2>
                </div>
                <div class="stat-card">
                    <p>En attente</p>
                    <h2>{{ $stats['candidatures_recentes']->where('statut', 'en attente')->count() ?? 0 }}</h2>
                </div>
                <div class="stat-card">
                    <p>Acceptées</p>
                    <h2>{{ $stats['candidatures_recentes']->where('statut', 'acceptée')->count() ?? 0 }}</h2>
                </div>
            </div>

            {{-- Actions rapides --}}
            <div class="action-grid" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('entreprise.offres.create') }}" class="action-btn primary">
                    <i class="fas fa-plus"></i> Publier une offre
                </a>
                <a href="{{ route('entreprise.demandes') }}" class="action-btn primary">
                    <i class="fas fa-inbox"></i> Candidatures & Demandes
                </a>
                <a href="{{ route('entreprise.stages.index') }}" class="action-btn primary">
                    <i class="fas fa-briefcase"></i> Mes stages
                </a>
                <a href="{{ route('entreprise.edit', $entreprise) }}" class="action-btn secondary">
                    <i class="fas fa-edit"></i> Modifier le profil
                </a>
                <a href="{{ route('offres.index') }}" class="action-btn secondary">
                    <i class="fas fa-eye"></i> Voir mes offres publiques
                </a>
            </div>

            {{-- Candidatures récentes --}}
            <div class="candidatures-card" data-aos="fade-up" data-aos-delay="300">
                <h3>Candidatures récentes</h3>
                <div class="divide-y divide-gray-200">
                    @forelse($stats['candidatures_recentes'] ?? [] as $candidature)
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
                                <p>{{ $candidature->user->name ?? 'Candidat inconnu' }}</p>
                                <span>Pour : {{ $candidature->offre->titre ?? 'Offre inconnue' }} • {{ $candidature->created_at->format('d/m/Y') }}</span>
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

            {{-- Informations entreprise --}}
            <div class="candidatures-card" data-aos="fade-up" data-aos-delay="400">
                <h3>Informations de l'entreprise</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nom</p>
                        <p class="font-semibold">{{ $entreprise->nom }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Secteur</p>
                        <p class="font-semibold">{{ $entreprise->secteur ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $entreprise->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Téléphone</p>
                        <p class="font-semibold">{{ $entreprise->telephone ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
        @endif
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
