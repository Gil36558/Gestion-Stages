@extends('layouts.app')

@section('title', 'Liste des entreprises')

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
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --background: #ffffff;
        --background-alt: #f8fafc;
        --border-color: #e5e7eb;
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .entreprises-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
    }

    .entreprises-header {
        text-align: center;
        margin-bottom: 3rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .entreprises-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1rem;
        position: relative;
    }

    .entreprises-header h1::after {
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

    .entreprises-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .entreprise-card {
        background: var(--background);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .entreprise-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-light);
    }

    .entreprise-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .entreprise-logo {
        width: 60px;
        height: 60px;
        background: var(--secondary-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--primary-blue);
        font-weight: bold;
    }

    .entreprise-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .entreprise-info p {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .entreprise-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .entreprise-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .entreprise-detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .entreprise-detail i {
        color: var(--primary-blue);
        width: 16px;
    }

    .entreprise-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .btn-demande {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-demande:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        color: white;
    }

    @media (max-width: 768px) {
        .entreprises-container {
            padding: 1rem;
        }
        
        .entreprises-header h1 {
            font-size: 2rem;
        }
        
        .entreprises-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="entreprises-container">
    <!-- En-tête -->
    <div class="entreprises-header" data-aos="fade-up" data-aos-duration="800">
        <h1>Entreprises Partenaires</h1>
        <p>Découvrez les entreprises qui proposent des stages et opportunités professionnelles</p>
    </div>

    <!-- Liste des entreprises -->
    @if($entreprises->count() > 0)
        <div class="entreprises-grid">
            @foreach($entreprises as $entreprise)
                <div class="entreprise-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="entreprise-header">
                        <div class="entreprise-logo">
                            {{ strtoupper(substr($entreprise->nom, 0, 2)) }}
                        </div>
                        <div class="entreprise-info">
                            <h3>{{ $entreprise->nom }}</h3>
                            <p>{{ $entreprise->secteur ?? 'Secteur non spécifié' }}</p>
                        </div>
                    </div>

                    @if($entreprise->description)
                        <p class="entreprise-description">
                            {{ $entreprise->description }}
                        </p>
                    @endif

                    <div class="entreprise-details">
                        @if($entreprise->adresse)
                            <div class="entreprise-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $entreprise->adresse }}</span>
                            </div>
                        @endif
                        
                        @if($entreprise->telephone)
                            <div class="entreprise-detail">
                                <i class="fas fa-phone"></i>
                                <span>{{ $entreprise->telephone }}</span>
                            </div>
                        @endif
                        
                        @if($entreprise->site_web)
                            <div class="entreprise-detail">
                                <i class="fas fa-globe"></i>
                                <a href="{{ $entreprise->site_web }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    Site web
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="entreprise-footer">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ $entreprise->offres->count() }} offre(s) disponible(s)
                        </div>
                        
                        @auth
                            @if(auth()->user()->role === 'etudiant')
                                <a href="{{ route('entreprise.show', $entreprise) }}" class="btn-demande">
                                    <i class="fas fa-eye"></i>
                                    Voir détails
                                </a>
                            @else
                                <a href="{{ route('entreprise.show', $entreprise) }}" class="btn-demande">
                                    <i class="fas fa-eye"></i>
                                    Voir détails
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-demande">
                                <i class="fas fa-sign-in-alt"></i>
                                Se connecter
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($entreprises->hasPages())
            <div class="flex justify-center mt-8" data-aos="fade-up" data-aos-delay="400">
                {{ $entreprises->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="text-center py-16" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune entreprise trouvée</h3>
            <p class="text-gray-500">Il n'y a pas encore d'entreprises inscrites sur la plateforme.</p>
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
