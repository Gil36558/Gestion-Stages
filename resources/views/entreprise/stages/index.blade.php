@extends('layouts.app')

@section('title', 'Gestion des Stages')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #3b82f6;
        --secondary: #64748b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        --purple: #8b5cf6;
        --indigo: #6366f1;
        --background: #ffffff;
        --surface: #f8fafc;
        --border: #e2e8f0;
        --text: #1e293b;
        --text-light: #64748b;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        min-height: 100vh;
    }

    .container-pro {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .header-card {
        background: linear-gradient(135deg, var(--background) 0%, var(--surface) 100%);
        border-radius: 2rem;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-xl);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .header-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light), var(--info));
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .header-subtitle {
        color: var(--text-light);
        font-size: 1.2rem;
        font-weight: 500;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--background);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: all 0.3s ease;
    }

    .stat-card.blue::before { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
    .stat-card.green::before { background: linear-gradient(90deg, var(--success), #34d399); }
    .stat-card.orange::before { background: linear-gradient(90deg, var(--warning), #fbbf24); }
    .stat-card.purple::before { background: linear-gradient(90deg, var(--purple), #a78bfa); }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-xl);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }

    .stat-icon.blue { background: linear-gradient(135deg, var(--primary), var(--primary-light)); }
    .stat-icon.green { background: linear-gradient(135deg, var(--success), #34d399); }
    .stat-icon.orange { background: linear-gradient(135deg, var(--warning), #fbbf24); }
    .stat-icon.purple { background: linear-gradient(135deg, var(--purple), #a78bfa); }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-light);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stages-grid {
        display: grid;
        gap: 2rem;
    }

    .stage-card {
        background: var(--background);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stage-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
    }

    .stage-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .stage-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.5rem;
    }

    .stage-status {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-en_cours { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
    .status-termine { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .status-en_attente_debut { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    .status-evalue { background: rgba(139, 92, 246, 0.1); color: var(--purple); }
    .status-valide { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-annule { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

    .stage-info {
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        color: var(--text-light);
        font-size: 0.95rem;
    }

    .info-row i {
        width: 20px;
        margin-right: 0.75rem;
        color: var(--primary);
    }

    .stage-description {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .stage-source {
        font-size: 0.85rem;
        color: var(--text-light);
        background: var(--surface);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .actions-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        box-shadow: var(--shadow);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #34d399);
        color: white;
        box-shadow: var(--shadow);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, var(--success));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning), #fbbf24);
        color: white;
        box-shadow: var(--shadow);
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #d97706, var(--warning));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger), #f87171);
        color: white;
        box-shadow: var(--shadow);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, var(--danger));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-purple {
        background: linear-gradient(135deg, var(--purple), #a78bfa);
        color: white;
        box-shadow: var(--shadow);
    }

    .btn-purple:hover {
        background: linear-gradient(135deg, #7c3aed, var(--purple));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-indigo {
        background: linear-gradient(135deg, var(--indigo), #818cf8);
        color: white;
        box-shadow: var(--shadow);
    }

    .btn-indigo:hover {
        background: linear-gradient(135deg, #4f46e5, var(--indigo));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
        background: var(--surface);
        color: var(--text);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--background);
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .quick-btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--background);
        border-radius: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--surface), #e2e8f0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--text-light);
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--text-light);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal.hidden {
        display: none;
    }

    .modal-content {
        background: var(--background);
        border-radius: 1.5rem;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
    }

    @media (max-width: 768px) {
        .container-pro {
            padding: 1rem;
        }

        .header-card {
            padding: 1.5rem;
        }

        .header-title {
            font-size: 2rem;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .stage-header {
            flex-direction: column;
            gap: 1rem;
        }

        .actions-container {
            flex-direction: column;
            align-items: stretch;
        }

        .btn {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-pro" data-aos="fade-up">
    <!-- En-tête -->
    <div class="header-card">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="header-title">
                    🏢 Gestion des Stages
                </h1>
                <p class="header-subtitle">
                    Suivez et gérez tous vos stages en cours et terminés
                </p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500 mb-1">Total des stages</div>
                <div class="text-3xl font-bold text-blue-600">{{ $stages->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-container" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $stages->where('statut', 'en_attente_debut')->count() }}</div>
            <div class="stat-label">En attente de début</div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon green">
                <i class="fas fa-play-circle"></i>
            </div>
            <div class="stat-number">{{ $stages->where('statut', 'en_cours')->count() }}</div>
            <div class="stat-label">En cours</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon orange">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $stages->where('statut', 'termine')->count() }}</div>
            <div class="stat-label">Terminés</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon purple">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-number">{{ $stages->whereIn('statut', ['evalue', 'valide'])->count() }}</div>
            <div class="stat-label">Évalués</div>
        </div>
    </div>

    @if($stages->isEmpty())
        <!-- État vide -->
        <div class="empty-state" data-aos="fade-up" data-aos-delay="200">
            <div class="empty-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <h3 class="empty-title">Aucun stage pour le moment</h3>
            <p class="empty-text">
                Les stages apparaîtront ici lorsque vous accepterez des candidatures ou des demandes de stage.<br>
                Commencez par publier des offres ou consulter vos demandes reçues.
            </p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('entreprise.demandes') }}" class="btn btn-primary">
                    <i class="fas fa-inbox"></i>
                    Voir les demandes
                </a>
                <a href="{{ route('entreprise.offres.create') }}" class="btn btn-secondary">
                    <i class="fas fa-plus"></i>
                    Publier une offre
                </a>
            </div>
        </div>
    @else
        <!-- Liste des stages -->
        <div class="stages-grid" data-aos="fade-up" data-aos-delay="200">
            @foreach($stages as $stage)
                <div class="stage-card">
                    <div class="stage-header">
                        <div class="flex-1">
                            <h3 class="stage-title">{{ $stage->titre }}</h3>
                            <span class="stage-status status-{{ $stage->statut }}">
                                @switch($stage->statut)
                                    @case('en_cours')
                                        🟢 En cours
                                        @break
                                    @case('termine')
                                        🏁 Terminé
                                        @break
                                    @case('en_attente_debut')
                                        ⏳ En attente
                                        @break
                                    @case('evalue')
                                        ⭐ Évalué
                                        @break
                                    @case('valide')
                                        ✅ Validé
                                        @break
                                    @case('annule')
                                        ❌ Annulé
                                        @break
                                    @default
                                        {{ ucfirst($stage->statut) }}
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <div class="stage-info">
                        <div class="info-row">
                            <i class="fas fa-user"></i>
                            <span><strong>{{ $stage->etudiant->name }}</strong> • {{ $stage->etudiant->email }}</span>
                        </div>
                        
                        <div class="info-row">
                            <i class="fas fa-calendar"></i>
                            <span>Du {{ $stage->date_debut->format('d/m/Y') }} au {{ $stage->date_fin->format('d/m/Y') }}</span>
                            @php
                                $duree = $stage->date_debut->diffInDays($stage->date_fin) + 1;
                            @endphp
                            <span class="ml-2 text-gray-400">({{ $duree }} jours)</span>
                        </div>

                        @if($stage->lieu)
                        <div class="info-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $stage->lieu }}</span>
                        </div>
                        @endif
                    </div>

                    @if($stage->description)
                        <p class="stage-description">
                            {{ Str::limit($stage->description, 150) }}
                        </p>
                    @endif

                    <div class="stage-source">
                        @if($stage->candidature_id)
                            📝 Issu d'une candidature à une offre
                        @elseif($stage->demande_stage_id)
                            📋 Issu d'une demande de stage directe
                        @else
                            📄 Stage créé directement
                        @endif
                    </div>

                    <!-- Actions principales -->
                    <div class="actions-container">
                        <a href="{{ route('stages.show', $stage) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            Voir détails
                        </a>

                        @if($stage->statut === 'en_cours')
                            <a href="{{ route('entreprise.journal.index', $stage) }}" class="btn btn-purple">
                                <i class="fas fa-book"></i>
                                Journal
                            </a>
                            
                            <a href="{{ route('entreprise.journal.calendrier', $stage) }}" class="btn btn-indigo">
                                <i class="fas fa-calendar-alt"></i>
                                Calendrier
                            </a>
                        @endif

                        @if($stage->statut === 'termine' && !$stage->note_entreprise)
                            <button onclick="openModal('evaluer-{{ $stage->id }}')" class="btn btn-success">
                                <i class="fas fa-star"></i>
                                Évaluer
                            </button>
                        @endif

                        @if(in_array($stage->statut, ['en_attente_debut', 'en_cours']))
                            <button onclick="openModal('annuler-{{ $stage->id }}')" class="btn btn-danger">
                                <i class="fas fa-times"></i>
                                Annuler
                            </button>
                        @endif
                    </div>

                    <!-- Actions rapides -->
                    <div class="quick-actions">
                        @if($stage->rapport_stage)
                            <a href="{{ route('stages.download', [$stage, 'rapport']) }}" class="btn btn-secondary quick-btn">
                                <i class="fas fa-file-pdf"></i>
                                Rapport
                            </a>
                        @endif

                        @if($stage->attestation_stage)
                            <a href="{{ route('stages.download', [$stage, 'attestation']) }}" class="btn btn-secondary quick-btn">
                                <i class="fas fa-certificate"></i>
                                Attestation
                            </a>
                        @endif

                        @if($stage->note_entreprise)
                            <span class="btn btn-success quick-btn">
                                <i class="fas fa-check"></i>
                                Évalué ({{ $stage->note_entreprise }}/20)
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Modals pour ce stage -->
                @if($stage->statut === 'termine' && !$stage->note_entreprise)
                    @include('entreprise.stages.modals.evaluer', ['stage' => $stage])
                @endif

                @if(in_array($stage->statut, ['en_attente_debut', 'en_cours']))
                    @include('entreprise.stages.modals.annuler', ['stage' => $stage])
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        @if($stages->hasPages())
            <div class="mt-8" data-aos="fade-up" data-aos-delay="300">
                {{ $stages->links() }}
            </div>
        @endif
    @endif
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

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Fermer les modals en cliquant à l'extérieur
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });

    // Fermer les modals avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal:not(.hidden)');
            modals.forEach(modal => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
        }
    });

    // Animation des cartes au survol
    document.querySelectorAll('.stage-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Animation des boutons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            btn.style.transform = 'translateY(-2px) scale(1.05)';
        });
        
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translateY(0) scale(1)';
        });
    });
</script>
@endpush
