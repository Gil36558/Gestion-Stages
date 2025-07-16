@extends('layouts.app')

@section('title', 'Tableau de bord - Entreprise')

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
        padding: 1.5rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        font-size: 0.95rem;
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s ease;
    }

    .alert:hover {
        transform: translateY(-2px);
    }

    .alert-warning {
        background: rgba(252, 211, 77, 0.1);
        border-left: 4px solid var(--warning-color);
        color: var(--text-primary);
    }

    .alert a {
        color: var(--warning-color);
        text-decoration: underline;
    }

    .alert a:hover {
        color: var(--primary-dark);
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

    .candidatures-card,
    .offres-card {
        background: var(--background);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 1.5rem;
    }

    .candidatures-card h3,
    .offres-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    .candidature-item,
    .offre-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .candidature-item:last-child,
    .offre-item:last-child {
        border-bottom: none;
    }

    .candidature-info p,
    .offre-info p {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .candidature-info span,
    .offre-info span {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .candidature-status,
    .offre-candidatures {
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

    .offre-candidatures {
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-blue);
    }

    .offre-item {
        background: var(--background-alt);
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .offre-actions a {
        margin-left: 1rem;
        color: var(--primary-blue);
        text-decoration: underline;
    }

    .offre-actions a:hover {
        color: var(--primary-dark);
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
        .card-grid {
            grid-template-columns: 1fr;
        }
        .action-grid {
            grid-template-columns: 1fr;
        }
        .dashboard-header h1 {
            font-size: 1.875rem;
        }
        .alert {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container" data-aos="fade-up" data-aos-duration="800">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <h1>Tableau de bord - {{ Auth::user()->name }}</h1>
            <p>Gérez vos offres de stage et candidatures</p>
        </div>

        <!--@if($profilIncomplet && $entreprise)
            <div class="alert alert-warning" data-aos="fade-up" data-aos-delay="100">
                <strong>Attention !</strong> Votre profil entreprise n’est pas complété. 
                <a href="{{ route('entreprise.edit', $entreprise->id) }}" class="underline">
                    Complétez-le maintenant.
                </a>
            </div>
        @elseif($profilIncomplet && !$entreprise)
            <div class="alert alert-warning" data-aos="fade-up" data-aos-delay="100">
                <strong>Attention !</strong> Vous n'avez pas encore créé votre profil entreprise. 
                <a href="{{ route('entreprise.create') }}" class="underline">
                    Créez-le maintenant.
                </a>
            </div>
        @endif -->

        <!-- Statistiques principales -->
        <div class="card-grid" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <p>Offres approuvées</p>
                <h2>{{ $stats['offres'] ?? 0 }}</h2>
            </div>
            <div class="stat-card">
                <p>Candidatures reçues</p>
                <h2>{{ $stats['candidatures'] ?? 0 }}</h2>
            </div>
            <div class="stat-card">
                <p>Stagiaires actuels</p>
                <h2>{{ $stats['stagiaires'] ?? 0 }}</h2>
            </div>
            <div class="stat-card">
                <p>Taux d'acceptation</p>
                <h2>{{ $stats['taux_acceptation'] ?? '0%' }}</h2>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="action-grid" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('entreprise.offres.create') }}" class="action-btn primary">
                <i class="fas fa-plus"></i> Publier une offre
            </a>
            <a href="{{ route('entreprise.candidatures') }}" class="action-btn secondary">
                <i class="fas fa-users"></i> Voir les candidatures
            </a>
            @if($entreprise)
                <a href="{{ route('entreprise.edit', $entreprise->id) }}" class="action-btn secondary">
                    <i class="fas fa-user-edit"></i> Profil d'entreprise
                </a>
            @else
                <a href="{{ route('entreprise.create') }}" class="action-btn secondary">
                    <i class="fas fa-user-plus"></i> Créer mon profil
                </a>
            @endif
        </div>

        <!-- Candidatures récentes -->
        <div class="candidatures-card" data-aos="fade-up" data-aos-delay="400">
            <h3>Candidatures récentes</h3>
            <div class="divide-y divide-gray-200">
                @forelse($stats['candidatures_recentes'] ?? [] as $candidature)
                    <div class="candidature-item">
                        <div class="candidature-info">
                            <p>{{ $candidature->etudiant->user->name ?? 'N/A' }}</p>
                            <span>{{ $candidature->offre->titre ?? 'Offre inconnue' }} – {{ $candidature->offre->type ?? '' }}</span>
                        </div>
                        <span class="candidature-status {{ str_replace('é', 'e', strtolower($candidature->statut)) }}">
                            {{ ucfirst($candidature->statut) }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Aucune candidature récente.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Offres actives -->
        @if($entreprise)
            <div class="offres-card" data-aos="fade-up" data-aos-delay="500">
                <h3>Offres actives</h3>
                <div class="space-y-4">
                    @foreach($entreprise->offres ?? [] as $offre)
                        <div class="offre-item">
                            <div class="offre-info">
                                <p>{{ $offre->titre }}</p>
                                <span>{{ $offre->lieu ?? 'Lieu inconnu' }} • {{ $offre->duree ?? '?' }} mois • {{ ucfirst($offre->type ?? 'Professionnel') }}</span>
                            </div>
                            <div class="offre-actions">
                                <span class="offre-candidatures">{{ $offre->candidatures->count() }} candidatures</span>
                                <a href="{{ route('entreprise.offres.edit', $offre->id) }}">Gérer</a>
                            </div>
                        </div>
                    @endforeach
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