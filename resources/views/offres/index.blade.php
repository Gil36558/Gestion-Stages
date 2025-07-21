@extends('layouts.app')

@section('title', 'Offres de stage')

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

    .offres-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
    }

    .offres-header {
        text-align: center;
        margin-bottom: 3rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .offres-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1rem;
        position: relative;
    }

    .offres-header h1::after {
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

    .offres-header p {
        font-size: 1.1rem;
        color: var(--text-secondary);
        margin-top: 1rem;
    }

    .search-filters {
        background: var(--background);
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .filter-control {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: var(--background);
    }

    .filter-control:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-filter {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .offres-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .offre-card {
        background: var(--background);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .offre-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.05), transparent);
        transition: left 0.5s ease;
    }

    .offre-card:hover::before {
        left: 100%;
    }

    .offre-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-light);
    }

    .offre-header-card {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .offre-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .offre-entreprise {
        font-size: 0.9rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .offre-type {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .type-academique {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .type-professionnel {
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-blue);
    }

    .type-les_deux {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
    }

    .offre-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .offre-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .offre-detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .offre-detail i {
        color: var(--primary-blue);
        width: 16px;
    }

    .offre-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .offre-date {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .btn-candidater {
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

    .btn-candidater:hover {
        background: var(--primary-dark);
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

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    @media (max-width: 768px) {
        .offres-container {
            padding: 1rem;
        }
        
        .offres-header h1 {
            font-size: 2rem;
        }
        
        .search-filters {
            padding: 1.5rem;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .offres-grid {
            grid-template-columns: 1fr;
        }
        
        .offre-header-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="offres-container">
    <!-- En-tête -->
    <div class="offres-header" data-aos="fade-up" data-aos-duration="800">
        <h1>Offres de Stage</h1>
        <p>Découvrez les meilleures opportunités de stage et lancez votre carrière</p>
    </div>

    <!-- Filtres de recherche -->
    <div class="search-filters" data-aos="fade-up" data-aos-delay="100">
        <form method="GET" action="{{ route('offres.index') }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Titre, entreprise, compétences..." 
                           class="filter-control">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Type de stage</label>
                    <select name="type" class="filter-control">
                        <option value="">Tous les types</option>
                        <option value="academique" {{ request('type') == 'academique' ? 'selected' : '' }}>Académique</option>
                        <option value="professionnel" {{ request('type') == 'professionnel' ? 'selected' : '' }}>Professionnel</option>
                        <option value="les_deux" {{ request('type') == 'les_deux' ? 'selected' : '' }}>Les deux</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Lieu</label>
                    <input type="text" name="lieu" value="{{ request('lieu') }}" 
                           placeholder="Ville, région..." 
                           class="filter-control">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Secteur</label>
                    <select name="secteur" class="filter-control">
                        <option value="">Tous les secteurs</option>
                        <option value="Technologie" {{ request('secteur') == 'Technologie' ? 'selected' : '' }}>Technologie</option>
                        <option value="Finance" {{ request('secteur') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Santé" {{ request('secteur') == 'Santé' ? 'selected' : '' }}>Santé</option>
                        <option value="Marketing" {{ request('secteur') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Commerce" {{ request('secteur') == 'Commerce' ? 'selected' : '' }}>Commerce</option>
                        <option value="Industrie" {{ request('secteur') == 'Industrie' ? 'selected' : '' }}>Industrie</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Résultats -->
    @if($offres->count() > 0)
        <div class="offres-grid">
            @foreach($offres as $offre)
                <div class="offre-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="offre-header-card">
                        <div class="flex-1">
                            <h3 class="offre-title">{{ $offre->titre }}</h3>
                            <div class="offre-entreprise">
                                <i class="fas fa-building"></i>
                                {{ $offre->entreprise->nom }}
                            </div>
                        </div>
                        <div class="offre-type type-{{ $offre->type_stage ?? 'les_deux' }}">
                            @switch($offre->type_stage)
                                @case('academique')
                                    Académique
                                    @break
                                @case('professionnel')
                                    Professionnel
                                    @break
                                @default
                                    Mixte
                            @endswitch
                        </div>
                    </div>

                    <p class="offre-description">
                        {{ Str::limit($offre->description, 150) }}
                    </p>

                    <div class="offre-details">
                        @if($offre->lieu)
                            <div class="offre-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $offre->lieu }}</span>
                            </div>
                        @endif
                        
                        @if($offre->duree)
                            <div class="offre-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $offre->duree }}</span>
                            </div>
                        @endif
                        
                        @if($offre->remuneration)
                            <div class="offre-detail">
                                <i class="fas fa-euro-sign"></i>
                                <span>{{ $offre->remuneration }}</span>
                            </div>
                        @endif
                        
                        @if($offre->niveau_etudes)
                            <div class="offre-detail">
                                <i class="fas fa-graduation-cap"></i>
                                <span>{{ $offre->niveau_etudes }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="offre-footer">
                        <div class="offre-date">
                            Publié le {{ $offre->created_at->format('d/m/Y') }}
                        </div>
                        
                        @auth
                            @if(auth()->user()->role === 'etudiant')
                                <a href="{{ route('offres.show', $offre) }}" 
                                   class="btn-candidater">
                                    <i class="fas fa-paper-plane"></i>
                                    Voir détails
                                </a>
                            @else
                                <a href="{{ route('offres.show', $offre) }}" class="btn-candidater">
                                    <i class="fas fa-eye"></i>
                                    Voir détails
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-candidater">
                                <i class="fas fa-sign-in-alt"></i>
                                Se connecter
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($offres->hasPages())
            <div class="pagination-wrapper" data-aos="fade-up" data-aos-delay="400">
                {{ $offres->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="empty-state" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-search"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune offre trouvée</h3>
            <p class="mb-4">
                @if(request()->hasAny(['search', 'type', 'lieu', 'secteur']))
                    Aucune offre ne correspond à vos critères de recherche.
                @else
                    Il n'y a pas encore d'offres de stage disponibles.
                @endif
            </p>
            @if(request()->hasAny(['search', 'type', 'lieu', 'secteur']))
                <a href="{{ route('offres.index') }}" class="btn-candidater">
                    <i class="fas fa-refresh mr-2"></i>
                    Voir toutes les offres
                </a>
            @endif
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

    // Auto-submit du formulaire de recherche avec debounce
    const searchInput = document.querySelector('input[name="search"]');
    const lieuInput = document.querySelector('input[name="lieu"]');
    let searchTimeout;

    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.querySelector('.search-filters form').submit();
        }, 500);
    }

    if (searchInput) {
        searchInput.addEventListener('input', debounceSearch);
    }
    
    if (lieuInput) {
        lieuInput.addEventListener('input', debounceSearch);
    }

    // Auto-submit pour les selects
    const selectInputs = document.querySelectorAll('.search-filters select');
    selectInputs.forEach(select => {
        select.addEventListener('change', () => {
            document.querySelector('.search-filters form').submit();
        });
    });
</script>
@endpush
