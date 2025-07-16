@extends('layouts.app')

@section('title', 'Inscription - PlateformeStages')

@push('styles')
<!-- AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<!-- Tailwind CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-blue: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #3b82f6;
        --secondary-blue: #eff6ff;
        --accent-color: #f59e0b;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --background: #ffffff;
        --background-alt: #f8fafc;
        --border-color: #e5e7eb;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --danger-color: #ef4444;
        --success-color: #10b981;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
        min-height: 100vh;
        color: var(--text-primary);
        line-height: 1.6;
    }

    .main-container {
        background: var(--background);
        border-radius: 20px;
        box-shadow: var(--shadow-xl);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        transition: all 0.3s ease;
        max-width: 600px;
        margin: 2rem auto; /* Réduit pour mieux s’aligner avec la navbar */
    }

    .main-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    }

    /* En-tête du formulaire */
    .form-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light) 100%);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0%, 100% { transform: translateX(-100%) translateY(-100%) rotate(0deg); }
        50% { transform: translateX(0%) translateY(0%) rotate(180deg); }
    }

    .icon-container {
        position: relative;
        z-index: 1;
        margin-bottom: 1rem;
    }

    .icon-bg {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        padding: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .icon-bg:hover {
        transform: scale(1.1);
        background: rgba(255, 255, 255, 0.3);
    }

    /* Formulaire */
    .form-body {
        padding: 2.5rem;
        background: var(--background);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        transition: color 0.3s ease;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: var(--background);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }

    .form-control:hover {
        border-color: var(--border-color);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
        background: rgba(239, 68, 68, 0.05);
    }

    .form-control.is-valid {
        border-color: var(--success-color);
        background: rgba(16, 185, 129, 0.05);
    }

    /* Messages d'erreur */
    .invalid-feedback {
        display: block;
        color: var(--danger-color);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 500;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Sélecteur de rôle */
    .role-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .role-option {
        padding: 1.5rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        background: var(--background);
    }

    .role-option::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .role-option:hover::before {
        left: 100%;
    }

    .role-option:hover {
        border-color: var(--primary-blue);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .role-option.active {
        border-color: var(--primary-blue);
        background: rgba(37, 99, 235, 0.05);
        transform: translateY(-2px);
    }

    .role-option input[type="radio"] {
        display: none;
    }

    .role-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary-blue);
    }

    /* Boutons */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
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

    .btn-secondary:hover {
        background: var(--background-alt);
        border-color: var(--primary-light);
        transform: translateY(-1px);
        color: var(--text-primary);
    }

    /* Pied de page */
    .form-footer {
        background: var(--background-alt);
        padding: 2rem;
        text-align: center;
        border-top: 1px solid var(--border-color);
    }

    /* Animations */
    .field-group {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Validation en temps réel */
    .form-control.validating {
        border-color: var(--accent-color);
    }

    /* Loader */
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Notifications toast */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }

    .toast.show {
        transform: translateX(0);
    }

    .toast.success {
        background: var(--success-color);
    }

    .toast.error {
        background: var(--danger-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .role-selector {
            grid-template-columns: 1fr;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .main-container {
            margin: 1rem;
            border-radius: 16px;
        }
    }
</style>
@endpush

@section('content')
<meta name="description" content="Inscrivez-vous sur PlateformeStages pour gérer vos stages et candidatures en tant qu'étudiant ou entreprise.">
<meta name="keywords" content="inscription, stages, gestion de stages, étudiants, entreprises">

<main class="min-h-screen py-8 px-4 with-form"> <!-- Ajout de with-form pour plus de padding -->
    <div class="container mx-auto max-w-2xl">
        <div class="main-container" data-aos="fade-up" data-aos-duration="800">
            <!-- En-tête -->
            <div class="form-header">
                <div class="icon-container">
                    <div class="icon-bg">
                        <svg id="icon-etudiant" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0118 9.25c0 2.21-2.686 4-6 4s-6-1.79-6-4c0-.651.164-1.265.45-1.828L12 14z"/>
                        </svg>
                        <svg id="icon-entreprise" class="w-8 h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <h1 id="form-title" class="text-2xl font-bold mb-2">Inscription Étudiant</h1>
                <p class="text-white text-sm">Créez votre compte pour accéder aux opportunités de stage</p>
            </div>

            <!-- Formulaire -->
            <div class="form-body">
                <form method="POST" action="{{ route('register') }}" id="registrationForm" novalidate>
                    @csrf

                    <!-- Sélecteur de rôle -->
                    <div class="role-selector" data-aos="fade-up" data-aos-delay="100">
                        <label class="role-option" for="role-etudiant">
                            <input type="radio" id="role-etudiant" name="role" value="etudiant" 
                                   {{ old('role', request()->query('role', 'etudiant')) == 'etudiant' ? 'checked' : '' }}>
                            <div class="role-icon">🎓</div>
                            <div class="font-semibold">Étudiant</div>
                            <div class="text-sm text-gray-600">Rechercher des stages</div>
                        </label>
                        <label class="role-option" for="role-entreprise">
                            <input type="radio" id="role-entreprise" name="role" value="entreprise" 
                                   {{ old('role', request()->query('role')) == 'entreprise' ? 'checked' : '' }}>
                            <div class="role-icon">🏢</div>
                            <div class="font-semibold">Entreprise</div>
                            <div class="text-sm text-gray-600">Proposer des stages</div>
                        </label>
                    </div>

                    <!-- Champs Étudiant -->
                    <div id="student-fields" class="space-y-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group field-group">
                                <label for="prenom" class="form-label">Prénom *</label>
                                <input id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" 
                                       placeholder="Votre prénom" class="form-control @error('prenom') is-invalid @enderror" 
                                       required data-validation="required,minlength:2">
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group field-group">
                                <label for="nom" class="form-label">Nom *</label>
                                <input id="nom" name="nom" type="text" value="{{ old('nom') }}" 
                                       placeholder="Votre nom" class="form-control @error('nom') is-invalid @enderror" 
                                       required data-validation="required,minlength:2">
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group field-group">
                            <label for="universite" class="form-label">Université / École *</label>
                            <input id="universite" name="universite" type="text" value="{{ old('universite') }}" 
                                   placeholder="Nom de votre établissement" class="form-control @error('universite') is-invalid @enderror" 
                                   required data-validation="required,minlength:3">
                            @error('universite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group field-group">
                                <label for="domaine" class="form-label">Domaine d'études *</label>
                                <input id="domaine" name="domaine" type="text" value="{{ old('domaine') }}" 
                                       placeholder="Ex: Informatique, Marketing..." class="form-control @error('domaine') is-invalid @enderror" 
                                       required data-validation="required,minlength:3">
                                @error('domaine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group field-group">
                                <label for="niveau" class="form-label">Niveau d'études *</label>
                                <select id="niveau" name="niveau" class="form-control @error('niveau') is-invalid @enderror" required>
                                    <option value="">Sélectionnez votre niveau</option>
                                    <option value="Bac+1" {{ old('niveau') == 'Bac+1' ? 'selected' : '' }}>Bac+1</option>
                                    <option value="Bac+2" {{ old('niveau') == 'Bac+2' ? 'selected' : '' }}>Bac+2</option>
                                    <option value="Bac+3" {{ old('niveau') == 'Bac+3' ? 'selected' : '' }}>Bac+3 (Licence)</option>
                                    <option value="Bac+4" {{ old('niveau') == 'Bac+4' ? 'selected' : '' }}>Bac+4 (Master 1)</option>
                                    <option value="Bac+5" {{ old('niveau') == 'Bac+5' ? 'selected' : '' }}>Bac+5 (Master 2)</option>
                                    <option value="Bac+6+" {{ old('niveau') == 'Bac+6+' ? 'selected' : '' }}>Bac+6 et plus</option>
                                </select>
                                @error('niveau')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Champs Entreprise -->
                    <div id="enterprise-fields" class="space-y-4 hidden" data-aos="fade-up" data-aos-delay="200">
                        <div class="form-group field-group">
                            <label for="nom_entreprise" class="form-label">Nom de l'entreprise *</label>
                            <input id="nom_entreprise" name="nom_entreprise" type="text" value="{{ old('nom_entreprise') }}" 
                                   placeholder="Nom de l'entreprise" class="form-control @error('nom_entreprise') is-invalid @enderror" 
                                   required data-validation="required,minlength:2">
                            @error('nom_entreprise')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group field-group">
                                <label for="secteur" class="form-label">Secteur d'activité *</label>
                                <select id="secteur" name="secteur" class="form-control @error('secteur') is-invalid @enderror" required>
                                    <option value="">Sélectionnez un secteur</option>
                                    <option value="Technologie" {{ old('secteur') == 'Technologie' ? 'selected' : '' }}>Technologie</option>
                                    <option value="Finance" {{ old('secteur') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="Santé" {{ old('secteur') == 'Santé' ? 'selected' : '' }}>Santé</option>
                                    <option value="Éducation" {{ old('secteur') == 'Éducation' ? 'selected' : '' }}>Éducation</option>
                                    <option value="Commerce" {{ old('secteur') == 'Commerce' ? 'selected' : '' }}>Commerce</option>
                                    <option value="Industrie" {{ old('secteur') == 'Industrie' ? 'selected' : '' }}>Industrie</option>
                                    <option value="Services" {{ old('secteur') == 'Services' ? 'selected' : '' }}>Services</option>
                                    <option value="Autre" {{ old('secteur') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('secteur')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group field-group">
                                <label for="taille" class="form-label">Taille de l'entreprise *</label>
                                <select id="taille" name="taille" class="form-control @error('taille') is-invalid @enderror" required>
                                    <option value="">Sélectionnez une taille</option>
                                    <option value="1-10" {{ old('taille') == '1-10' ? 'selected' : '' }}>1-10 employés</option>
                                    <option value="11-50" {{ old('taille') == '11-50' ? 'selected' : '' }}>11-50 employés</option>
                                    <option value="51-200" {{ old('taille') == '51-200' ? 'selected' : '' }}>51-200 employés</option>
                                    <option value="201-500" {{ old('taille') == '201-500' ? 'selected' : '' }}>201-500 employés</option>
                                    <option value="500+" {{ old('taille') == '500+' ? 'selected' : '' }}>500+ employés</option>
                                </select>
                                @error('taille')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group field-group">
                            <label for="adresse" class="form-label">Adresse *</label>
                            <input id="adresse" name="adresse" type="text" value="{{ old('adresse') }}" 
                                   placeholder="Adresse complète" class="form-control @error('adresse') is-invalid @enderror" 
                                   required data-validation="required,minlength:10">
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group field-group">
                            <label for="site_web" class="form-label">Site web</label>
                            <input id="site_web" name="site_web" type="url" value="{{ old('site_web') }}" 
                                   placeholder="https://votre-site.com" class="form-control @error('site_web') is-invalid @enderror" 
                                   data-validation="url">
                            @error('site_web')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group field-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="4" 
                                      placeholder="Décrivez votre entreprise, vos activités..." 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      data-validation="minlength:10">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations de connexion -->
                    <div class="space-y-4 mt-6" data-aos="fade-up" data-aos-delay="300">
                        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informations de connexion</h3>
                        
                        <div class="form-group field-group">
                            <label for="email" class="form-label">Adresse email *</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" 
                                   placeholder="votre@email.com" class="form-control @error('email') is-invalid @enderror" 
                                   required data-validation="required,email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group field-group">
                            <label for="telephone" class="form-label">Téléphone *</label>
                            <input id="telephone" name="telephone" type="tel" value="{{ old('telephone') }}" 
                                   placeholder="Ex: 06 12 34 56 78" class="form-control @error('telephone') is-invalid @enderror" 
                                   required data-validation="required,phone">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group field-group">
                                <label for="password" class="form-label">Mot de passe *</label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" 
                                           placeholder="Mot de passe sécurisé" class="form-control @error('password') is-invalid @enderror" 
                                           required data-validation="required,password">
                                    <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" 
                                            onclick="togglePassword('password')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <div class="password-strength" id="password-strength">
                                        <div class="flex space-x-1 mb-1">
                                            <div class="strength-bar" data-level="1"></div>
                                            <div class="strength-bar" data-level="2"></div>
                                            <div class="strength-bar" data-level="3"></div>
                                            <div class="strength-bar" data-level="4"></div>
                                        </div>
                                        <div class="text-xs text-gray-500" id="strength-text">Saisissez un mot de passe</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group field-group">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" type="password" 
                                           placeholder="Confirmez votre mot de passe" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           required data-validation="required,match:password">
                                    <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" 
                                            onclick="togglePassword('password_confirmation')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Conditions d'utilisation -->
                        <div class="form-group field-group">
                            <label class="flex items-start space-x-3">
                                <input type="checkbox" name="terms" id="terms" required 
                                       class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">
                                    J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">conditions d'utilisation</a> 
                                    et la <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">politique de confidentialité</a>
                                </span>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="mt-8" data-aos="fade-up" data-aos-delay="400">
                        <button type="submit" class="btn-primary" id="submit-btn">
                            <span id="submit-text">Créer mon compte</span>
                            <span id="submit-loading" class="hidden">
                                <span class="spinner"></span>
                                Création en cours...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Pied de page -->
            <div class="form-footer">
                <p class="text-gray-600 text-sm mb-4">
                    Déjà inscrit ? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Se connecter
                    </a>
                </p>
                <a href="{{ route('welcome') }}" class="btn-secondary">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <!-- Toast notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    // Initialisation AOS
    AOS.init({
        once: true,
        offset: 100,
        duration: 800,
        easing: 'ease-out-cubic',
    });

    // Validation en temps réel
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const studentFields = document.getElementById('student-fields');
    const enterpriseFields = document.getElementById('enterprise-fields');
    const iconEtudiant = document.getElementById('icon-etudiant');
    const iconEntreprise = document.getElementById('icon-entreprise');
    const formTitle = document.getElementById('form-title');
    const form = document.getElementById('registrationForm');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');

    function handleRoleChange() {
        const selectedRole = document.querySelector('input[name="role"]:checked').value;
        
        studentFields.classList.toggle('hidden', selectedRole !== 'etudiant');
        enterpriseFields.classList.toggle('hidden', selectedRole !== 'entreprise');
        
        iconEtudiant.classList.toggle('hidden', selectedRole !== 'etudiant');
        iconEntreprise.classList.toggle('hidden', selectedRole !== 'entreprise');
        
        formTitle.textContent = selectedRole === 'entreprise' ? 'Inscription Entreprise' : 'Inscription Étudiant';
        
        document.querySelectorAll('.role-option').forEach(option => {
            option.classList.remove('active');
        });
        document.querySelector(`input[value="${selectedRole}"]`).closest('.role-option').classList.add('active');
        
        const visibleFields = selectedRole === 'etudiant' ? studentFields : enterpriseFields;
        visibleFields.querySelectorAll('.field-group').forEach((field, index) => {
            field.style.animationDelay = `${index * 0.1}s`;
        });
    }

    roleInputs.forEach(input => {
        input.addEventListener('change', handleRoleChange);
    });

    handleRoleChange();

    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
            
            if (this.id === 'password') {
                updatePasswordStrength(this.value);
            }
        });
    });

    function validateField(field) {
        const validationRules = field.dataset.validation;
        if (!validationRules) return;

        const rules = validationRules.split(',');
        let isValid = true;
        let errorMessage = '';

        rules.forEach(rule => {
            if (!isValid) return;

            if (rule === 'required' && !field.value.trim()) {
                isValid = false;
                errorMessage = 'Ce champ est requis';
            } else if (rule === 'email' && field.value && !isValidEmail(field.value)) {
                isValid = false;
                errorMessage = 'Format d\'email invalide';
            } else if (rule === 'phone' && field.value && !isValidPhone(field.value)) {
                isValid = false;
                errorMessage = 'Format de téléphone invalide';
            } else if (rule === 'url' && field.value && !isValidUrl(field.value)) {
                isValid = false;
                errorMessage = 'Format d\'URL invalide';
            } else if (rule === 'password' && field.value && !isValidPassword(field.value)) {
                isValid = false;
                errorMessage = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre';
            } else if (rule.startsWith('minlength:')) {
                const minLength = parseInt(rule.split(':')[1]);
                if (field.value && field.value.length < minLength) {
                    isValid = false;
                    errorMessage = `Minimum ${minLength} caractères requis`;
                }
            } else if (rule.startsWith('match:')) {
                const targetField = rule.split(':')[1];
                const targetValue = document.getElementById(targetField).value;
                if (field.value && field.value !== targetValue) {
                    isValid = false;
                    errorMessage = 'Les mots de passe ne correspondent pas';
                }
            }
        });

        const feedbackElement = field.parentNode.querySelector('.invalid-feedback');
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            if (feedbackElement) feedbackElement.textContent = '';
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            if (feedbackElement) feedbackElement.textContent = errorMessage;
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
        return phoneRegex.test(phone.replace(/\s/g, ''));
    }

    function isValidUrl(url) {
        try { new URL(url); return true; } catch { return false; }
    }

    function isValidPassword(password) {
        return password.length >= 8 && /[A-Z]/.test(password) && /[a-z]/.test(password) && /\d/.test(password);
    }

    function updatePasswordStrength(password) {
        const strengthBars = document.querySelectorAll('.strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        let strength = 0;
        let text = 'Très faible';
        
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        const strengthLevels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
        const strengthColors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#10b981'];
        
        text = strengthLevels[Math.min(strength, 4)];
        
        strengthBars.forEach((bar, index) => {
            if (index < strength) {
                bar.style.backgroundColor = strengthColors[Math.min(strength - 1, 4)];
                bar.style.width = '100%';
            } else {
                bar.style.backgroundColor = '#e5e7eb';
                bar.style.width = '100%';
            }
        });
        
        strengthText.textContent = text;
        strengthText.style.color = strengthColors[Math.min(strength - 1, 4)] || '#6b7280';
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isFormValid = true;
        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.closest('.hidden')) {
                validateField(input);
                if (input.classList.contains('is-invalid')) isFormValid = false;
            }
        });

        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            isFormValid = false;
            showToast('Veuillez accepter les conditions d\'utilisation', 'error');
        }

        if (isFormValid) {
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            this.submit();
        } else {
            showToast('Veuillez corriger les erreurs dans le formulaire', 'error');
            const firstError = document.querySelector('.is-invalid');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        
        document.getElementById('toast-container').appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        .strength-bar { height: 4px; flex: 1; background-color: #e5e7eb; border-radius: 2px; transition: all 0.3s ease; }
        .password-strength { margin-top: 0.5rem; }
    `;
    document.head.appendChild(styleSheet);
</script>
@endpush