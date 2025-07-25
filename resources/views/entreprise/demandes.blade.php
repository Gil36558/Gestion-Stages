@extends('layouts.app')

@section('title', 'Demandes de stage reçues')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-blue: #2563eb;
        --primary-dark: #1e40af;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --background: #ffffff;
        --background-alt: #f8fafc;
        --border-color: #e5e7eb;
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .header-section {
        text-align: center;
        margin-bottom: 2rem;
    }

    .header-section h1 {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .header-section p {
        color: var(--text-secondary);
        font-size: 1.1rem;
    }

    .tabs-container {
        margin-bottom: 2rem;
    }

    .tabs-nav {
        display: flex;
        background: var(--background-alt);
        border-radius: 1rem;
        padding: 0.5rem;
        gap: 0.5rem;
    }

    .tab-button {
        flex: 1;
        padding: 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
    }

    .tab-button.active {
        background: var(--primary-blue);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .tab-button:not(.active) {
        color: var(--text-secondary);
    }

    .tab-button:not(.active):hover {
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-blue);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .demande-card {
        background: var(--background);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .demande-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
    }

    .demande-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .demande-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .demande-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .demande-meta span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-badge.en_attente {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
    }

    .status-badge.validee {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .status-badge.refusee {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
    }

    .status-badge.acceptee {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .demande-content {
        margin-bottom: 1.5rem;
    }

    .demande-content p {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .demande-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        background: var(--background-alt);
        padding: 1rem;
        border-radius: 0.5rem;
    }

    .detail-item .label {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .detail-item .value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .actions-section {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary-blue);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
    }

    .btn-success {
        background: var(--success-color);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-danger {
        background: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-secondary {
        background: var(--background-alt);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .filter-section {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        background: var(--background);
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .demande-header {
            flex-direction: column;
            gap: 1rem;
        }

        .demande-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .actions-section {
            flex-direction: column;
        }

        .tabs-nav {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-custom" data-aos="fade-up">
    <div class="header-section">
        <h1>Demandes de stage reçues 📋</h1>
        <p>Gérez les candidatures aux offres et les demandes directes des étudiants</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Onglets -->
    <div class="tabs-container">
        <div class="tabs-nav">
            <button class="tab-button active" onclick="switchTab('candidatures')">
                <i class="fas fa-briefcase mr-2"></i>
                Candidatures aux offres ({{ $candidatures->count() }})
            </button>
            <button class="tab-button" onclick="switchTab('demandes')">
                <i class="fas fa-file-alt mr-2"></i>
                Demandes directes ({{ $demandes->count() }})
            </button>
        </div>
    </div>

    <!-- Contenu Candidatures aux offres -->
    <div id="candidatures-tab" class="tab-content active">
        <div class="filter-section">
            <select class="filter-select" onchange="filterCandidatures(this.value)">
                <option value="">Tous les statuts</option>
                <option value="en attente">En attente</option>
                <option value="acceptée">Acceptées</option>
                <option value="refusée">Refusées</option>
            </select>
        </div>

        @forelse($candidatures as $candidature)
            <div class="demande-card candidature-item" data-status="{{ $candidature->statut }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="demande-header">
                    <div class="demande-info">
                        <h3>{{ $candidature->user->name }}</h3>
                        <p class="text-sm text-gray-600">Candidature pour : <strong>{{ $candidature->offre->titre }}</strong></p>
                    </div>
                    <span class="status-badge {{ str_replace(' ', '_', strtolower($candidature->statut)) }}">
                        {{ ucfirst($candidature->statut) }}
                    </span>
                </div>

                <div class="demande-meta">
                    <span><i class="fas fa-calendar"></i> {{ $candidature->created_at->format('d/m/Y à H:i') }}</span>
                    <span><i class="fas fa-envelope"></i> {{ $candidature->user->email }}</span>
                    @if($candidature->date_debut_disponible)
                        <span><i class="fas fa-clock"></i> Disponible dès le {{ \Carbon\Carbon::parse($candidature->date_debut_disponible)->format('d/m/Y') }}</span>
                    @endif
                </div>

                @if($candidature->message)
                    <div class="demande-content">
                        <p><strong>Message :</strong> {{ $candidature->message }}</p>
                    </div>
                @endif

                <div class="demande-details">
                    @if($candidature->duree_souhaitee)
                        <div class="detail-item">
                            <div class="label">Durée souhaitée</div>
                            <div class="value">{{ $candidature->duree_souhaitee }} mois</div>
                        </div>
                    @endif
                    @if($candidature->competences)
                        <div class="detail-item">
                            <div class="label">Compétences</div>
                            <div class="value">{{ Str::limit($candidature->competences, 50) }}</div>
                        </div>
                    @endif
                    @if($candidature->experiences)
                        <div class="detail-item">
                            <div class="label">Expériences</div>
                            <div class="value">{{ Str::limit($candidature->experiences, 50) }}</div>
                        </div>
                    @endif
                </div>

                <div class="actions-section">
                    <a href="{{ route('candidatures.show', $candidature) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Voir détails
                    </a>
                    
                    @if($candidature->statut === 'en attente')
                        <form method="POST" action="{{ route('entreprise.candidatures.offres.approve', $candidature) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Accepter cette candidature ?')">
                                <i class="fas fa-check"></i> Accepter
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('entreprise.candidatures.offres.reject', $candidature) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Refuser cette candidature ?')">
                                <i class="fas fa-times"></i> Refuser
                            </button>
                        </form>
                    @endif

                    @if($candidature->cv)
                        <a href="{{ route('candidatures.download', ['candidature' => $candidature, 'type' => 'cv']) }}" class="btn btn-secondary">
                            <i class="fas fa-download"></i> CV
                        </a>
                    @endif
                    
                    @if($candidature->lettre)
                        <a href="{{ route('candidatures.download', ['candidature' => $candidature, 'type' => 'lettre']) }}" class="btn btn-secondary">
                            <i class="fas fa-download"></i> Lettre
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Aucune candidature reçue</h3>
                <p>Les candidatures aux offres que vous publiez apparaîtront ici.</p>
            </div>
        @endforelse
    </div>

    <!-- Contenu Demandes directes -->
    <div id="demandes-tab" class="tab-content">
        <div class="filter-section">
            <select class="filter-select" onchange="filterDemandes(this.value)">
                <option value="">Tous les statuts</option>
                <option value="en attente">En attente</option>
                <option value="validée">Validées</option>
                <option value="refusée">Refusées</option>
            </select>
        </div>

        @forelse($demandes as $demande)
            <div class="demande-card demande-item" data-status="{{ $demande->statut }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="demande-header">
                    <div class="demande-info">
                        <h3>{{ $demande->etudiants->first()->name ?? 'Étudiant inconnu' }}</h3>
                        <p class="text-sm text-gray-600">{{ $demande->objet }}</p>
                    </div>
                    <span class="status-badge {{ str_replace(' ', '_', strtolower($demande->statut)) }}">
                        {{ ucfirst($demande->statut) }}
                    </span>
                </div>

                <div class="demande-meta">
                    <span><i class="fas fa-calendar"></i> {{ $demande->created_at->format('d/m/Y à H:i') }}</span>
                    <span><i class="fas fa-tag"></i> {{ ucfirst($demande->type) }}</span>
                    <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($demande->periode_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($demande->periode_fin)->format('d/m/Y') }}</span>
                </div>

                <div class="demande-details">
                    @if($demande->etablissement)
                        <div class="detail-item">
                            <div class="label">Établissement</div>
                            <div class="value">{{ $demande->etablissement }}</div>
                        </div>
                    @endif
                    @if($demande->filiere)
                        <div class="detail-item">
                            <div class="label">Filière</div>
                            <div class="value">{{ $demande->filiere }}</div>
                        </div>
                    @endif
                    @if($demande->niveau_etudes)
                        <div class="detail-item">
                            <div class="label">Niveau d'études</div>
                            <div class="value">{{ $demande->niveau_etudes }}</div>
                        </div>
                    @endif
                    @if($demande->objectifs_stage)
                        <div class="detail-item">
                            <div class="label">Objectifs</div>
                            <div class="value">{{ Str::limit($demande->objectifs_stage, 100) }}</div>
                        </div>
                    @endif
                </div>

                <div class="actions-section">
                    <a href="{{ route('demandes.show', $demande) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Voir détails
                    </a>
                    
                    @if($demande->statut === 'en attente')
                        <form method="POST" action="{{ route('entreprise.demandes.approve', $demande) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success" onclick="return confirm('Valider cette demande ?')">
                                <i class="fas fa-check"></i> Valider
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('entreprise.demandes.reject', $demande) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Refuser cette demande ?')">
                                <i class="fas fa-times"></i> Refuser
                            </button>
                        </form>
                    @endif

                    @if($demande->cv)
                        <a href="{{ Storage::url($demande->cv) }}" target="_blank" class="btn btn-secondary">
                            <i class="fas fa-download"></i> CV
                        </a>
                    @endif
                    
                    @if($demande->lettre_motivation)
                        <a href="{{ Storage::url($demande->lettre_motivation) }}" target="_blank" class="btn btn-secondary">
                            <i class="fas fa-download"></i> Lettre
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>Aucune demande directe reçue</h3>
                <p>Les demandes de stage directes des étudiants apparaîtront ici.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        offset: 100,
        duration: 600,
        easing: 'ease-out-cubic',
    });

    function switchTab(tabName) {
        // Masquer tous les contenus
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Désactiver tous les boutons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });
        
        // Activer le contenu et bouton sélectionnés
        document.getElementById(tabName + '-tab').classList.add('active');
        event.target.classList.add('active');
    }

    function filterCandidatures(status) {
        const items = document.querySelectorAll('.candidature-item');
        items.forEach(item => {
            if (status === '' || item.dataset.status === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function filterDemandes(status) {
        const items = document.querySelectorAll('.demande-item');
        items.forEach(item => {
            if (status === '' || item.dataset.status === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endpush
