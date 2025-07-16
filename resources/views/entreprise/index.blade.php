@extends('layouts.app')

@section('title', 'Entreprises partenaires')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-dark: #1e40af;
            --secondary-blue: #eff6ff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-light: #f8fafc;
            --border-light: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background: var(--secondary-blue);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .container {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 2rem;
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 3rem;
            height: 3px;
            background: var(--primary-blue);
            border-radius: 2px;
        }

        .entreprise-card {
            border-radius: 1rem;
            border: 1px solid var(--border-light);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background-color: white;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease-in-out;
        }

        .entreprise-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .entreprise-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .entreprise-info {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .entreprise-info::before {
            content: '•';
            color: var(--primary-blue);
            font-weight: 700;
            margin-right: 0.5rem;
        }

        .btn-outline-secondary {
            border-color: var(--border-light);
            color: var(--text-primary);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-secondary:hover {
            background-color: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }

        .btn-stage {
            background-color: var(--primary-blue);
            color: white;
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.5rem 1.25rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-stage:hover {
            background-color: var(--primary-dark);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-stage svg {
            width: 1rem;
            height: 1rem;
        }

        .alert-success {
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            h1 {
                font-size: 1.75rem;
            }

            .entreprise-card {
                margin-left: 0;
                margin-right: 0;
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-5" data-aos="fade-up" data-aos-duration="800">
    <h1 class="mb-4 text-center fw-bold text-primary">Nos entreprises partenaires</h1>

    @if(session('success'))
        <div class="alert alert-success" data-aos="fade-in" data-aos-delay="100">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($entreprises as $entreprise)
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="entreprise-card">
                    <div class="entreprise-title">{{ $entreprise->nom }}</div>
                    <div class="entreprise-info"><strong>Email :</strong> {{ $entreprise->email ?? 'Non spécifié' }}</div>
                    <div class="entreprise-info"><strong>Téléphone :</strong> {{ $entreprise->telephone ?? 'Non spécifié' }}</div>
                    <div class="entreprise-info"><strong>Adresse :</strong> {{ $entreprise->adresse ?? 'Non spécifiée' }}</div>
                    <div class="entreprise-info"><strong>Secteur :</strong> {{ $entreprise->secteur ?? 'Non spécifié' }}</div>
                    <a href="{{ route('entreprise.show', $entreprise->id) }}" class="btn btn-outline-secondary mt-3">
                        Voir le profil complet
                    </a>
                    @auth
                        @if(auth()->user()->estEtudiant())
                            <a href="{{ route('demande.stage.choix', ['entreprise_id' => $entreprise->id]) }}" class="btn-stage mt-2">
                                Faire une demande de stage
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="100">
                <p>Aucune entreprise enregistrée pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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