@extends('layouts.app')

@section('title', 'Mes candidatures')

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
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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

    .candidature-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .candidature-info p {
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

    .candidature-message {
        background: var(--background-alt);
        border-radius: 12px;
        padding: 1rem;
        margin: 1rem 0;
        border-left: 4px solid var(--primary-blue);
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

    .btn-delete {
        background: var(--danger-color);
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-1px);
        color: white;
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
    }
</style>
@endpush

@section('content')
<div class="candidatures-container">
    <!-- En-tête -->
    <div class="candidatures-header" data-aos="fade-up" data-aos-duration="800">
        <h1>Mes Candidatures</h1>
        <p>Suivez l'état de vos candidatures aux offres de stage</p>
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

    <!-- Candidatures -->
    @if($candidatures->count() > 0)
        <div class="candidatures-grid">
            @foreach($candidatures as $candidature)
                <div class="candidature-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="candidature-header">
                        <div class="candidature-info">
                            <h3>{{ $candidature->offre->titre }}</h3>
                            <p><i class="fas fa-building mr-1"></i> {{ $candidature->offre->entreprise->nom }}</p>
                            <p><i class="fas fa-map-marker-alt mr-1"></i> {{ $candidature->offre->lieu ?? 'Lieu non spécifié' }}</p>
                            <p><i class="fas fa-calendar mr-1"></i> Candidature envoyée le {{ $candidature->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="candidature-status status-{{ str_replace(' ', '_', strtolower($candidature->statut)) }}">
                            {{ ucfirst($candidature->statut) }}
                        </div>
                    </div>

                    <div class="candidature-content">
                        <p class="text-sm text-gray-600 mb-3">
                            {{ Str::limit($candidature->offre->description, 150) }}
                        </p>

                        @if($candidature->message)
                            <div class="candidature-message">
                                <p><strong>Votre message :</strong></p>
                                <p class="text-sm">{{ $candidature->message }}</p>
                            </div>
                        @endif

                        @if($candidature->motif_refus)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                <p class="text-sm text-red-700">
                                    <strong>Motif de refus :</strong> {{ $candidature->motif_refus }}
                                </p>
                            </div>
                        @endif

                        <!-- Informations sur l'offre -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <strong>Date de début :</strong><br>
                                    {{ \Carbon\Carbon::parse($candidature->offre->date_debut)->format('d/m/Y') }}
                                </div>
                                @if($candidature->offre->date_fin)
                                <div>
                                    <strong>Date de fin :</strong><br>
                                    {{ \Carbon\Carbon::parse($candidature->offre->date_fin)->format('d/m/Y') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="candidature-actions">
                        <a href="{{ route('offres.show', $candidature->offre) }}" class="btn-action btn-view">
                            <i class="fas fa-eye"></i>
                            Voir l'offre
                        </a>
                        
                        @if($candidature->statut === 'en attente')
                            <form method="POST" action="{{ route('candidatures.destroy', $candidature) }}" 
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir retirer cette candidature ?')">
                                    <i class="fas fa-trash"></i>
                                    Retirer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($candidatures->hasPages())
            <div class="flex justify-center mt-8" data-aos="fade-up" data-aos-delay="400">
                {{ $candidatures->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="empty-state" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-inbox"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune candidature envoyée</h3>
            <p class="mb-4">Vous n'avez pas encore envoyé de candidatures aux offres de stage.</p>
            <a href="{{ route('offres.index') }}" class="btn-action btn-view">
                <i class="fas fa-search mr-2"></i>
                Découvrir les offres
            </a>
        </div>
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
</script>
@endpush
