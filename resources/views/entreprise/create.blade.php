@extends('layouts.app')

@section('title', 'Créer votre profil entreprise')

@push('styles')
<!-- AOS CSS pour les animations -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<!-- Tailwind CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Font Awesome pour les icônes -->
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
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --background: #ffffff;
        --background-alt: #f8fafc;
        --border-color: #e5e7eb;
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .form-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-card {
        background: var(--background);
        border-radius: 1.5rem;
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 2rem;
        width: 100%;
        max-width: 600px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .form-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-title::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: var(--primary-blue);
        border-radius: 2px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 1rem;
        color: var(--text-primary);
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-input:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .form-select {
        @apply form-input appearance-none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }

    .form-textarea {
        @apply form-input;
        resize: vertical;
        min-height: 6rem;
    }

    .error-message {
        font-size: 0.75rem;
        color: var(--danger-color);
        margin-top: 0.25rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .alert-warning {
        @apply bg-yellow-100 border border-yellow-400 text-yellow-700;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-cancel {
        @apply bg-gray-300 text-gray-700 hover:bg-gray-400;
    }

    .btn-submit {
        @apply bg-blue-600 text-white hover:bg-blue-700;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .form-card {
            padding: 1.5rem;
        }
        .form-title {
            font-size: 1.5rem;
        }
        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container" data-aos="fade-up" data-aos-duration="800">
    <div class="form-card">
        <h1 class="form-title">Créer votre profil entreprise</h1>
        
        @if (session('warning'))
            <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
            </div>
        @endif

        <form method="POST" action="{{ route('entreprise.store') }}" enctype="multipart/form-data" data-aos="fade-up" data-aos-delay="100">
            @csrf

            <!-- Nom de l'entreprise -->
            <div class="form-group">
                <label for="nom" class="form-label">Nom de l'entreprise <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="nom" 
                       id="nom" 
                       value="{{ old('nom') }}"
                       class="form-input"
                       required>
                @error('nom')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Secteur -->
            <div class="form-group">
                <label for="secteur" class="form-label">Secteur d'activité <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="secteur" 
                       id="secteur" 
                       value="{{ old('secteur') }}"
                       class="form-input"
                       required>
                @error('secteur')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ville -->
            <div class="form-group">
                <label for="ville" class="form-label">Ville <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="ville" 
                       id="ville" 
                       value="{{ old('ville') }}"
                       class="form-input"
                       required>
                @error('ville')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Taille -->
            <div class="form-group">
                <label for="taille" class="form-label">Taille de l'entreprise <span class="text-red-500">*</span></label>
                <select name="taille" 
                        id="taille" 
                        class="form-select"
                        required>
                    <option value="">Sélectionnez une taille</option>
                    <option value="petite" {{ old('taille') == 'petite' ? 'selected' : '' }}>Petite (1-50 employés)</option>
                    <option value="moyenne" {{ old('taille') == 'moyenne' ? 'selected' : '' }}>Moyenne (51-250 employés)</option>
                    <option value="grande" {{ old('taille') == 'grande' ? 'selected' : '' }}>Grande (250+ employés)</option>
                </select>
                @error('taille')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="form-textarea">{{ old('description') }}</textarea>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Site web -->
            <div class="form-group">
                <label for="site_web" class="form-label">Site web</label>
                <input type="url" 
                       name="site_web" 
                       id="site_web" 
                       value="{{ old('site_web') }}"
                       class="form-input"
                       placeholder="https://www.exemple.com">
                @error('site_web')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo -->
            <div class="form-group">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" 
                       name="logo" 
                       id="logo" 
                       accept="image/*"
                       class="form-input">
                @error('logo')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('dashboard') }}" 
                   class="btn btn-cancel">
                    Annuler
                </a>
                <button type="submit" 
                        class="btn btn-submit">
                    Créer le profil
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
</script>
@endpush