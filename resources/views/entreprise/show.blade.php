@extends('layouts.app')

@section('title', 'Détail Entreprise')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-dark: #1e40af;
            --secondary-blue: #eff6ff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-light: #f8fafc;
            --border-light: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --gradient-primary: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        }

        body {
            background: var(--secondary-blue);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .main-container {
            max-width: 3xl;
            margin-left: auto;
            margin-right: auto;
            padding: 2.5rem 1rem;
        }

        .card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .title-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4rem;
            height: 4rem;
            background: var(--gradient-primary);
            border-radius: 50%;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
        }

        .title-icon img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .main-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .info-list {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .info-list strong {
            color: var(--text-primary);
            margin-right: 0.5rem;
        }

        .info-list a {
            color: var(--primary-blue);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .info-list a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-stage {
            background: var(--primary-blue);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-stage:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-stage svg {
            width: 1rem;
            height: 1rem;
        }

        .btn-back {
            background: var(--bg-light);
            color: var(--text-primary);
            font-weight: 500;
            padding: 0.375rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }

        .btn-back:hover {
            background: white;
            color: var(--primary-blue);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-back svg {
            width: 1rem;
            height: 1rem;
        }

        .offers-section {
            margin-top: 2.5rem;
        }

        .offer-item {
            border: 1px solid var(--border-light);
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .offer-item:hover {
            background: var(--bg-light);
        }

        .offer-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .offer-details {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .offer-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .offer-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main-title {
                font-size: 1.75rem;
            }

            .card {
                padding: 1.5rem;
            }

            .offer-item {
                padding: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="main-container" data-aos="fade-up" data-aos-duration="800">
    <div class="card" data-aos="zoom-in" data-aos-delay="100">
        <!-- Bouton Retour -->
        <div class="mb-6 text-left">
            <a href="{{ route('entreprise.index') }}" class="btn-back">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>
        </div>

        <!-- Logo entreprise (si disponible) -->
        @if($entreprise->logo)
            <div class="title-container">
                <div class="title-icon">
                    <img src="{{ asset('storage/' . $entreprise->logo) }}" alt="Logo {{ $entreprise->nom }}" class="h-24 object-contain">
                </div>
            </div>
        @endif

        <h1 class="main-title">{{ $entreprise->nom }}</h1>

        <div class="space-y-4 text-gray-700">
            <div class="info-list"><strong>Secteur :</strong> {{ $entreprise->secteur }}</div>
            <div class="info-list"><strong>Ville :</strong> {{ $entreprise->ville ?? 'Non renseignée' }}</div>
            <div class="info-list"><strong>Email :</strong> {{ $entreprise->email }}</div>
            <div class="info-list"><strong>Site web :</strong>
                @if($entreprise->site_web)
                    <a href="{{ $entreprise->site_web }}" target="_blank" rel="noopener" class="text-blue-600 underline hover:text-blue-800">
                        {{ $entreprise->site_web }}
                    </a>
                @else
                    Non renseigné
                @endif
            </div>
            <div class="info-list"><strong>Description :</strong><br>{{ $entreprise->description ?? 'Aucune description disponible.' }}</div>
        </div>

        <!-- Bouton demande de stage -->
        @auth
            @if(auth()->user()->role === 'etudiant')
                <div class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Intéressé par cette entreprise ?</h3>
                        <p class="text-gray-600 mb-4">Postulez dès maintenant pour un stage académique ou professionnel</p>
                        <a href="{{ route('demande.stage.choix', ['entreprise_id' => $entreprise->id]) }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Faire une demande de stage
                        </a>
                    </div>
                </div>
            @endif
        @else
            <div class="mt-8 p-6 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Vous souhaitez postuler ?</h3>
                    <p class="text-gray-600 mb-4">Connectez-vous pour faire une demande de stage</p>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter
                    </a>
                </div>
            </div>
        @endauth
    </div>

    <!-- Offres proposées -->
    @if($entreprise->offres && $entreprise->offres->count())
        <div class="offers-section card" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Offres disponibles</h2>
            <ul class="space-y-3">
                @foreach($entreprise->offres as $offre)
                    <li class="offer-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="offer-title">{{ $offre->titre }}</div>
                        <div class="offer-details">{{ $offre->lieu }} • {{ $offre->duree }} mois</div>
                        <a href="{{ route('offres.show', $offre) }}" class="offer-link mt-2 inline-block">
                            Voir les détails
                        </a>
                    </li>
                @endforeach
            </ul>
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