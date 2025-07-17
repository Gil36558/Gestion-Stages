@extends('layouts.app')

@section('title', 'Type de demande de stage')

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
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --background: #ffffff;
        --background-alt: #f8fafc;
        --border-color: #e5e7eb;
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .choice-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .choice-card {
        background: var(--background);
        border-radius: 1.5rem;
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
        max-width: 900px;
        padding: 2rem;
    }

    .choice-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .choice-header h1 {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .choice-header p {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }

    .choice-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .choice-option {
        background: var(--background-alt);
        border: 2px solid var(--border-color);
        border-radius: 1.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .choice-option::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .choice-option:hover::before {
        left: 100%;
    }

    .choice-option:hover {
        border-color: var(--primary-blue);
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
    }

    .choice-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--primary-blue);
    }

    .choice-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .choice-description {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .choice-features {
        list-style: none;
        padding: 0;
        margin-bottom: 2rem;
    }

    .choice-features li {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .choice-features li i {
        color: var(--primary-blue);
        margin-right: 0.5rem;
        width: 16px;
    }

    .choice-btn {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        text-decoration: none;
        display: inline-block;
    }

    .choice-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        color: white;
    }

    .back-btn {
        background: var(--background);
        color: var(--text-primary);
        border: 2px solid var(--border-color);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .back-btn:hover {
        background: var(--background-alt);
        border-color: var(--primary-light);
        transform: translateY(-1px);
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .choice-container {
            padding: 1rem;
        }
        
        .choice-grid {
            grid-template-columns: 1fr;
        }
        
        .choice-header h1 {
            font-size: 1.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="choice-container" data-aos="fade-up" data-aos-duration="800">
    <div class="choice-card">
        <div class="choice-header">
            <h1>Choisissez le type de stage</h1>
            <p>Sélectionnez le type de demande de stage qui correspond à votre situation</p>
        </div>

        <div class="choice-grid">
            <!-- Stage Académique -->
            <div class="choice-option" data-aos="fade-up" data-aos-delay="100">
                <div class="choice-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="choice-title">Stage Académique</h3>
                <p class="choice-description">
                    Stage obligatoire dans le cadre de votre cursus universitaire, 
                    avec suivi pédagogique et évaluation académique.
                </p>
                <ul class="choice-features">
                    <li><i class="fas fa-check"></i> Durée flexible selon le cursus</li>
                    <li><i class="fas fa-check"></i> Possibilité de stage en binôme</li>
                    <li><i class="fas fa-check"></i> Suivi par un tuteur académique</li>
                    <li><i class="fas fa-check"></i> Rapport de stage requis</li>
                    <li><i class="fas fa-check"></i> Évaluation notée</li>
                </ul>
                <a href="{{ route('demande.stage.form', ['type' => 'academique', 'entreprise_id' => request('entreprise_id')]) }}" 
                   class="choice-btn">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Demande académique
                </a>
            </div>

            <!-- Stage Professionnel -->
            <div class="choice-option" data-aos="fade-up" data-aos-delay="200">
                <div class="choice-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="choice-title">Stage Professionnel</h3>
                <p class="choice-description">
                    Stage d'insertion professionnelle pour acquérir une expérience 
                    pratique et développer vos compétences métier.
                </p>
                <ul class="choice-features">
                    <li><i class="fas fa-check"></i> Durée adaptée aux besoins</li>
                    <li><i class="fas fa-check"></i> Expérience professionnelle</li>
                    <li><i class="fas fa-check"></i> Possibilité de rémunération</li>
                    <li><i class="fas fa-check"></i> Développement de compétences</li>
                    <li><i class="fas fa-check"></i> Opportunité d'embauche</li>
                </ul>
                <a href="{{ route('demande.stage.form', ['type' => 'professionnel', 'entreprise_id' => request('entreprise_id')]) }}" 
                   class="choice-btn">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Demande professionnelle
                </a>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="text-center" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('entreprise.index') }}" class="back-btn">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux entreprises
            </a>
        </div>
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
