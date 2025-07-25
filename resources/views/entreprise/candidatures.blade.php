@extends('layouts.app')

@section('title', 'Candidatures reçues')

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

    .candidatures-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
    }

    .candidatures-header {
        text-align: center;
        margin-bottom: 3rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .candidatures-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1rem;
        position: relative;
    }

    .candidatures-header h1::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: var(--primary-blue);
        border-radius: 2px;
    }

    .candidatures-header p {
        font-size: 1.1rem;
        color: var(--text-secondary);
        margin-top: 1rem;
    }

    .candidatures-grid {
        display: grid;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .candidature-card {
        background: var(--background);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .candidature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.05), transparent);
        transition: left 0.5s ease;
    }

    .candidature-card:hover::before {
        left: 100%;
    }

    .candidature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-light);
    }

    .candidature-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .candidat-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .candidat-info p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .candidature-status {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .status-en_attente {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
    }

    .status-acceptee {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .status-refusee {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
    }

    .candidature-content {
        margin-bottom: 1.5rem;
    }

    .offre-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .candidature-message {
        background: var(--background-alt);
        border-radius: 12px;
        padding: 1rem;
        margin: 1rem 0;
        border-left: 4px solid var(--primary-blue);
    }

    .candidature-message p {
        color: var(--text-secondary);
        font-style: italic;
        margin: 0;
    }

    .candidature-files {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .file-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--background-alt);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        color: var(--text-primary);
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .file-link:hover {
        background: var(--secondary-blue);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .candidature-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }

    .btn-accept {
        background: var(--success-color);
        color: white;
    }

    .btn-accept:hover {
        background: #059669;
        transform: translateY(-1px);
        color: white;
    }

    .btn-reject {
        background: var(--danger-color);
        color: white;
    }

    .btn-reject:hover {
        background: #dc2626;
        transform: translateY(-1px);
        color: white;
    }

    .btn-view {
        background: var(--background-alt);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-view:hover {
        background: var(--secondary-blue);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--border-color);
        margin-bottom: 1rem;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    @media (max-width: 768px) {
        .candidatures-container {
            padding: 1rem;
        }
        
        .candidatures-header h1 {
            font-size: 2rem;
        }
        
        .candidature-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .candidature-actions {
            flex-direction: column;
        }
        
        .candidature-files {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="candidatures-container">
    <!-- En-tête -->
    <div class="candidatures-header" data-aos="fade-up" data-aos-duration="800">
        <h1>Candidatures Reçues</h1>
        <p>Gérez les candidatures pour vos offres de stage</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" data-aos="fade-in">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" data-aos="fade-in">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Onglets pour séparer candidatures et demandes -->
    <div class="mb-6" data-aos="fade-up">
        <div class="flex border-b border-gray-200">
            <button onclick="showTab('candidatures')" id="tab-candidatures" 
                    class="px-6 py-3 font-medium text-sm border-b-2 border-blue-500 text-blue-600 bg-blue-50">
                Candidatures aux offres ({{ $candidatures->count() }})
            </button>
            <button onclick="showTab('demandes')" id="tab-demandes" 
                    class="px-6 py-3 font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                Demandes directes ({{ $demandesStages->count() }})
            </button>
        </div>
    </div>

    <!-- Candidatures aux offres -->
    <div id="content-candidatures" class="tab-content">
        @if($candidatures->count() > 0)
            <div class="candidatures-grid">
                @foreach($candidatures as $candidature)
                <div class="candidature-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="candidature-header">
                        <div class="candidat-info">
                            <h3>{{ $candidature->user->prenom }} {{ $candidature->user->name }}</h3>
                            <p><i class="fas fa-envelope mr-1"></i> {{ $candidature->user->email }}</p>
                            @if($candidature->user->telephone)
                                <p><i class="fas fa-phone mr-1"></i> {{ $candidature->user->telephone }}</p>
                            @endif
                            @if($candidature->user->ecole)
                                <p><i class="fas fa-university mr-1"></i> {{ $candidature->user->ecole }}</p>
                            @endif
                        </div>
                        <div class="candidature-status status-{{ str_replace(' ', '_', strtolower($candidature->statut)) }}">
                            {{ ucfirst($candidature->statut) }}
                        </div>
                    </div>

                    <div class="candidature-content">
                        <div class="offre-title">
                            <i class="fas fa-briefcase mr-2"></i>
                            Candidature pour : {{ $candidature->offre->titre }}
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-calendar mr-1"></i>
                            Candidature envoyée le {{ $candidature->created_at->format('d/m/Y à H:i') }}
                        </p>

                        @if($candidature->message)
                            <div class="candidature-message">
                                <p><strong>Message du candidat :</strong></p>
                                <p>{{ $candidature->message }}</p>
                            </div>
                        @endif

                        <!-- Fichiers -->
                        <div class="candidature-files">
                            @if($candidature->cv)
                                <a href="{{ route('candidatures.download', [$candidature, 'cv']) }}" 
                                   class="file-link" target="_blank">
                                    <i class="fas fa-file-pdf text-red-500"></i>
                                    Télécharger CV
                                </a>
                            @endif
                            
                            @if($candidature->lettre)
                                <a href="{{ route('candidatures.download', [$candidature, 'lettre']) }}" 
                                   class="file-link" target="_blank">
                                    <i class="fas fa-file-alt text-blue-500"></i>
                                    Télécharger Lettre
                                </a>
                            @endif
                        </div>

                        @if($candidature->motif_refus)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                <p class="text-sm text-red-700">
                                    <strong>Motif de refus :</strong> {{ $candidature->motif_refus }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="candidature-actions">
                        @if($candidature->statut === 'en attente')
                            <form method="POST" action="{{ route('entreprise.candidatures.offres.approve', $candidature) }}" 
                                  style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-accept" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir accepter cette candidature ?')">
                                    <i class="fas fa-check"></i>
                                    Accepter
                                </button>
                            </form>
                            
                            <button type="button" class="btn-action btn-reject" 
                                    onclick="showRejectModal({{ $candidature->id }})">
                                <i class="fas fa-times"></i>
                                Refuser
                            </button>
                        @endif
                        
                        <a href="{{ route('candidatures.show', $candidature) }}" class="btn-action btn-view">
                            <i class="fas fa-eye"></i>
                            Voir détails
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($candidatures->hasPages())
                <div class="pagination-wrapper" data-aos="fade-up" data-aos-delay="400">
                    {{ $candidatures->appends(['demandes' => request('demandes')])->links() }}
                </div>
            @endif
        @else
            <!-- État vide -->
            <div class="empty-state" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-inbox"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune candidature reçue</h3>
                <p class="mb-4">Vous n'avez pas encore reçu de candidatures pour vos offres de stage.</p>
                <a href="{{ route('entreprise.offres.create') }}" class="btn-action btn-accept">
                    <i class="fas fa-plus mr-2"></i>
                    Publier une offre
                </a>
            </div>
        @endif
    </div>

    <!-- Demandes de stage directes -->
    <div id="content-demandes" class="tab-content hidden">
        @if($demandesStages->count() > 0)
            <div class="candidatures-grid">
                @foreach($demandesStages as $demande)
                    <div class="candidature-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="candidature-header">
                            <div class="candidat-info">
                                @if($demande->etudiants->count() > 0)
                                    @php $etudiant = $demande->etudiants->first() @endphp
                                    <h3>{{ $etudiant->prenom }} {{ $etudiant->name }}</h3>
                                    <p><i class="fas fa-envelope mr-1"></i> {{ $etudiant->email }}</p>
                                @else
                                    <h3>Demande anonyme</h3>
                                    <p><i class="fas fa-envelope mr-1"></i> {{ $demande->email ?? 'Email non fourni' }}</p>
                                @endif
                                @if($demande->telephone)
                                    <p><i class="fas fa-phone mr-1"></i> {{ $demande->telephone }}</p>
                                @endif
                                @if($demande->etablissement)
                                    <p><i class="fas fa-university mr-1"></i> {{ $demande->etablissement }}</p>
                                @endif
                            </div>
                            <div class="candidature-status status-{{ str_replace(' ', '_', strtolower($demande->statut)) }}">
                                {{ ucfirst($demande->statut) }}
                            </div>
                        </div>

                        <div class="candidature-content">
                            <div class="offre-title">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Demande de stage {{ $demande->type }} : {{ $demande->objet }}
                            </div>
                            
                            <p class="text-sm text-gray-500 mb-3">
                                <i class="fas fa-calendar mr-1"></i>
                                Période souhaitée : {{ \Carbon\Carbon::parse($demande->periode_debut)->format('d/m/Y') }} 
                                au {{ \Carbon\Carbon::parse($demande->periode_fin)->format('d/m/Y') }}
                            </p>

                            @if($demande->objectifs_stage)
                                <div class="candidature-message">
                                    <p><strong>Objectifs du stage :</strong></p>
                                    <p>{{ $demande->objectifs_stage }}</p>
                                </div>
                            @endif

                            <!-- Fichiers -->
                            <div class="candidature-files">
                                @if($demande->cv)
                                    <a href="{{ Storage::url($demande->cv) }}" 
                                       class="file-link" target="_blank">
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                        Télécharger CV
                                    </a>
                                @endif
                                
                                @if($demande->lettre_motivation)
                                    <a href="{{ Storage::url($demande->lettre_motivation) }}" 
                                       class="file-link" target="_blank">
                                        <i class="fas fa-file-alt text-blue-500"></i>
                                        Lettre de motivation
                                    </a>
                                @endif

                                @if($demande->portfolio)
                                    <a href="{{ Storage::url($demande->portfolio) }}" 
                                       class="file-link" target="_blank">
                                        <i class="fas fa-folder text-purple-500"></i>
                                        Portfolio
                                    </a>
                                @endif
                            </div>

                            <!-- Informations supplémentaires -->
                            @if($demande->competences_techniques || $demande->experiences_professionnelles)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3">
                                    @if($demande->competences_techniques)
                                        <p class="text-sm text-gray-700 mb-2">
                                            <strong>Compétences :</strong> {{ $demande->competences_techniques }}
                                        </p>
                                    @endif
                                    @if($demande->experiences_professionnelles)
                                        <p class="text-sm text-gray-700">
                                            <strong>Expériences :</strong> {{ $demande->experiences_professionnelles }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="candidature-actions">
                            @if($demande->statut === 'en attente')
                                <form method="POST" action="{{ route('entreprise.demandes.approve', $demande) }}" 
                                      style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-action btn-accept" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir accepter cette demande ?')">
                                        <i class="fas fa-check"></i>
                                        Accepter
                                    </button>
                                </form>
                                
                                <button type="button" class="btn-action btn-reject" 
                                        onclick="showRejectDemandeModal({{ $demande->id }})">
                                    <i class="fas fa-times"></i>
                                    Refuser
                                </button>
                            @endif
                            
                            <a href="{{ route('demandes.show', $demande) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                Voir détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($demandesStages->hasPages())
                <div class="pagination-wrapper" data-aos="fade-up" data-aos-delay="400">
                    {{ $demandesStages->appends(['candidatures' => request('candidatures')])->links() }}
                </div>
            @endif
        @else
            <!-- État vide -->
            <div class="empty-state" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-graduation-cap"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune demande de stage reçue</h3>
                <p class="mb-4">Vous n'avez pas encore reçu de demandes de stage directes.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de refus -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Refuser la candidature</h3>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="motif_refus" class="block text-sm font-medium text-gray-700 mb-2">
                    Motif du refus (optionnel)
                </label>
                <textarea id="motif_refus" name="motif_refus" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Expliquez brièvement pourquoi vous refusez cette candidature..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideRejectModal()" 
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Refuser
                </button>
            </div>
        </form>
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

    // Gestion des onglets
    function showTab(tabName) {
        // Masquer tous les contenus
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Réinitialiser tous les onglets
        document.querySelectorAll('[id^="tab-"]').forEach(tab => {
            tab.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Afficher le contenu sélectionné
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Activer l'onglet sélectionné
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
    }

    function showRejectModal(candidatureId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = '/entreprise/candidatures/' + candidatureId + '/reject';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function showRejectDemandeModal(demandeId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = '/entreprise/demandes/' + demandeId + '/reject';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('motif_refus').value = '';
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideRejectModal();
        }
    });
</script>
@endpush
