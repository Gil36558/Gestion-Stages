@extends('layouts.app')

@section('title', 'Détails de la demande de stage')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-blue: #2563eb;
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
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .demande-card {
        background: var(--background);
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .demande-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .demande-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
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

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-section {
        background: var(--background-alt);
        border-radius: 0.75rem;
        padding: 1.5rem;
    }

    .info-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-item {
        margin-bottom: 1rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: var(--text-primary);
        font-size: 1rem;
    }

    .documents-section {
        background: var(--background);
        border-radius: 0.75rem;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .document-item {
        background: var(--background-alt);
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .document-item:hover {
        transform: translateY(-2px);
    }

    .document-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary-blue);
    }

    .document-link {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .document-link:hover {
        text-decoration: underline;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--primary-blue);
        color: white;
        text-decoration: none;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: background 0.3s ease;
        margin-bottom: 2rem;
    }

    .back-button:hover {
        background: #1e40af;
    }

    .etudiants-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .etudiant-badge {
        background: var(--primary-blue);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .documents-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-custom" data-aos="fade-up">
    <!-- Bouton retour -->
    @if(auth()->user()->estEntreprise())
        <a href="{{ route('entreprise.demandes') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Retour aux demandes
        </a>
    @else
        <a href="{{ route('etudiant.dashboard') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
    @endif

    <!-- En-tête de la demande -->
    <div class="demande-card" data-aos="fade-up" data-aos-delay="100">
        <div class="demande-header">
            <h1>{{ $demande->objet ?? 'Demande de stage ' . ucfirst($demande->type) }}</h1>
            <div class="flex justify-center items-center gap-4 mt-4">
                <span class="status-badge {{ str_replace(' ', '_', strtolower($demande->statut)) }}">
                    {{ ucfirst($demande->statut) }}
                </span>
                <span class="text-gray-500">
                    <i class="fas fa-calendar mr-2"></i>
                    Créée le {{ $demande->created_at->format('d/m/Y à H:i') }}
                </span>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="info-grid">
            <!-- Informations générales -->
            <div class="info-section" data-aos="fade-up" data-aos-delay="200">
                <h3>
                    <i class="fas fa-info-circle text-blue-600"></i>
                    Informations générales
                </h3>
                
                <div class="info-item">
                    <div class="info-label">Type de stage</div>
                    <div class="info-value">{{ ucfirst($demande->type) }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Période</div>
                    <div class="info-value">
                        Du {{ \Carbon\Carbon::parse($demande->periode_debut)->format('d/m/Y') }}
                        au {{ \Carbon\Carbon::parse($demande->periode_fin)->format('d/m/Y') }}
                        <span class="text-sm text-gray-500">
                            ({{ \Carbon\Carbon::parse($demande->periode_debut)->diffInDays(\Carbon\Carbon::parse($demande->periode_fin)) }} jours)
                        </span>
                    </div>
                </div>

                @if($demande->periode)
                    <div class="info-item">
                        <div class="info-label">Période spécifiée</div>
                        <div class="info-value">{{ $demande->periode }}</div>
                    </div>
                @endif

                @if($demande->mode)
                    <div class="info-item">
                        <div class="info-label">Mode</div>
                        <div class="info-value">{{ ucfirst($demande->mode) }}</div>
                    </div>
                @endif
            </div>

            <!-- Entreprise -->
            <div class="info-section" data-aos="fade-up" data-aos-delay="300">
                <h3>
                    <i class="fas fa-building text-green-600"></i>
                    Entreprise
                </h3>
                
                <div class="info-item">
                    <div class="info-label">Nom</div>
                    <div class="info-value">{{ $demande->entreprise->nom }}</div>
                </div>

                @if($demande->entreprise->secteur)
                    <div class="info-item">
                        <div class="info-label">Secteur</div>
                        <div class="info-value">{{ $demande->entreprise->secteur }}</div>
                    </div>
                @endif

                @if($demande->entreprise->adresse)
                    <div class="info-item">
                        <div class="info-label">Adresse</div>
                        <div class="info-value">{{ $demande->entreprise->adresse }}</div>
                    </div>
                @endif

                @if($demande->entreprise->email)
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:{{ $demande->entreprise->email }}" class="text-blue-600 hover:underline">
                                {{ $demande->entreprise->email }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Étudiant(s) -->
            <div class="info-section" data-aos="fade-up" data-aos-delay="400">
                <h3>
                    <i class="fas fa-user-graduate text-purple-600"></i>
                    Étudiant{{ $demande->etudiants->count() > 1 ? 's' : '' }}
                </h3>
                
                <div class="etudiants-list">
                    @foreach($demande->etudiants as $etudiant)
                        <span class="etudiant-badge">{{ $etudiant->name }}</span>
                    @endforeach
                </div>

                @if($demande->nom_binome)
                    <div class="info-item mt-3">
                        <div class="info-label">Binôme spécifié</div>
                        <div class="info-value">{{ $demande->nom_binome }}</div>
                    </div>
                @endif

                @if($demande->email_binome)
                    <div class="info-item">
                        <div class="info-label">Email binôme</div>
                        <div class="info-value">{{ $demande->email_binome }}</div>
                    </div>
                @endif
            </div>

            <!-- Objectifs et compétences -->
            @if($demande->objectifs_stage || $demande->competences_a_developper)
                <div class="info-section" data-aos="fade-up" data-aos-delay="500">
                    <h3>
                        <i class="fas fa-target text-orange-600"></i>
                        Objectifs et compétences
                    </h3>
                    
                    @if($demande->objectifs_stage)
                        <div class="info-item">
                            <div class="info-label">Objectifs du stage</div>
                            <div class="info-value">{{ $demande->objectifs_stage }}</div>
                        </div>
                    @endif

                    @if($demande->competences_a_developper)
                        <div class="info-item">
                            <div class="info-label">Compétences à développer</div>
                            <div class="info-value">{{ $demande->competences_a_developper }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Documents -->
        <div class="documents-section" data-aos="fade-up" data-aos-delay="600">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-file-alt text-red-600 mr-2"></i>
                Documents joints
            </h3>
            
            <div class="documents-grid">
                @if($demande->cv)
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <a href="{{ Storage::url($demande->cv) }}" target="_blank" class="document-link">
                            Curriculum Vitae
                        </a>
                    </div>
                @endif

                @if($demande->lettre_motivation)
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <a href="{{ Storage::url($demande->lettre_motivation) }}" target="_blank" class="document-link">
                            Lettre de motivation
                        </a>
                    </div>
                @endif

                @if($demande->recommandation)
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <a href="{{ Storage::url($demande->recommandation) }}" target="_blank" class="document-link">
                            Lettre de recommandation
                        </a>
                    </div>
                @endif

                @if($demande->piece_identite)
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <a href="{{ Storage::url($demande->piece_identite) }}" target="_blank" class="document-link">
                            Pièce d'identité
                        </a>
                    </div>
                @endif

                @if($demande->portfolio)
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <a href="{{ Storage::url($demande->portfolio) }}" target="_blank" class="document-link">
                            Portfolio
                        </a>
                    </div>
                @endif
            </div>

            @if(!$demande->cv && !$demande->lettre_motivation && !$demande->recommandation && !$demande->piece_identite && !$demande->portfolio)
                <p class="text-gray-500 text-center py-4">Aucun document joint à cette demande.</p>
            @endif
        </div>

        <!-- Actions pour l'entreprise -->
        @if(auth()->user()->estEntreprise() && $demande->statut === 'en attente')
            <div class="flex justify-center gap-4 mt-6" data-aos="fade-up" data-aos-delay="700">
                <form method="POST" action="{{ route('entreprise.demandes.approve', $demande) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                            onclick="return confirm('Valider cette demande de stage ?')">
                        <i class="fas fa-check mr-2"></i> Valider la demande
                    </button>
                </form>
                
                <form method="POST" action="{{ route('entreprise.demandes.reject', $demande) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                            onclick="return confirm('Refuser cette demande de stage ?')">
                        <i class="fas fa-times mr-2"></i> Refuser la demande
                    </button>
                </form>
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
        duration: 600,
        easing: 'ease-out-cubic',
    });
</script>
@endpush
