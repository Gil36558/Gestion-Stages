@extends('layouts.app')

@section('title', 'Créer le profil entreprise')

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
        --danger-color: #ef4444;
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
        width: 100%;
        max-width: 800px;
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light) 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .form-body {
        padding: 2rem;
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

    .form-control.is-invalid {
        border-color: var(--danger-color);
        background: rgba(239, 68, 68, 0.05);
    }

    .invalid-feedback {
        display: block;
        color: var(--danger-color);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

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

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
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

    .file-upload {
        position: relative;
        display: inline-block;
        cursor: pointer;
        width: 100%;
    }

    .file-upload input[type=file] {
        position: absolute;
        left: -9999px;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        background: var(--background-alt);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-label:hover {
        border-color: var(--primary-blue);
        background: var(--secondary-blue);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1rem;
        }
        
        .form-body {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container" data-aos="fade-up" data-aos-duration="800">
    <div class="form-card">
        <!-- En-tête -->
        <div class="form-header">
            <div class="mb-4">
                <i class="fas fa-building text-4xl mb-4"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Créer le profil de votre entreprise</h1>
            <p class="text-white text-sm opacity-90">Complétez les informations pour commencer à publier des offres de stage</p>
        </div>

        <!-- Formulaire -->
        <div class="form-body">
            <form method="POST" action="{{ route('entreprise.store') }}" enctype="multipart/form-data" id="entrepriseForm">
                @csrf

                <!-- Informations de base -->
                <div class="mb-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations générales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="nom" class="form-label">Nom de l'entreprise *</label>
                            <input id="nom" name="nom" type="text" value="{{ old('nom', Auth::user()->name) }}" 
                                   placeholder="Nom de votre entreprise" class="form-control @error('nom') is-invalid @enderror" 
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
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
                                <option value="Marketing" {{ old('secteur') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Immobilier" {{ old('secteur') == 'Immobilier' ? 'selected' : '' }}>Immobilier</option>
                                <option value="Autre" {{ old('secteur') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('secteur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="taille" class="form-label">Taille de l'entreprise *</label>
                            <select id="taille" name="taille" class="form-control @error('taille') is-invalid @enderror" required>
                                <option value="">Sélectionnez une taille</option>
                                <option value="petite" {{ old('taille') == 'petite' ? 'selected' : '' }}>Petite (1-50 employés)</option>
                                <option value="moyenne" {{ old('taille') == 'moyenne' ? 'selected' : '' }}>Moyenne (51-250 employés)</option>
                                <option value="grande" {{ old('taille') == 'grande' ? 'selected' : '' }}>Grande (250+ employés)</option>
                            </select>
                            @error('taille')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ville" class="form-label">Ville *</label>
                            <input id="ville" name="ville" type="text" value="{{ old('ville') }}" 
                                   placeholder="Ville où se situe l'entreprise" class="form-control @error('ville') is-invalid @enderror" 
                                   required>
                            @error('ville')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_web" class="form-label">Site web</label>
                        <input id="site_web" name="site_web" type="url" value="{{ old('site_web') }}" 
                               placeholder="https://votre-site.com" class="form-control @error('site_web') is-invalid @enderror">
                        @error('site_web')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Description</h3>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Présentation de l'entreprise</label>
                        <textarea id="description" name="description" rows="5" 
                                  placeholder="Décrivez votre entreprise, vos activités, votre culture d'entreprise..." 
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-gray-500 text-xs mt-1">Cette description sera visible par les étudiants</small>
                    </div>
                </div>

                <!-- Logo -->
                <div class="mb-6" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Logo (optionnel)</h3>
                    
                    <div class="form-group">
                        <label for="logo" class="form-label">Logo de l'entreprise</label>
                        <div class="file-upload">
                            <input type="file" id="logo" name="logo" accept="image/*" class="@error('logo') is-invalid @enderror">
                            <label for="logo" class="file-upload-label">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Cliquez pour sélectionner un logo</p>
                                    <p class="text-xs text-gray-400">PNG, JPG jusqu'à 2MB</p>
                                </div>
                            </label>
                        </div>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8" data-aos="fade-up" data-aos-delay="400">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Créer le profil entreprise
                    </button>
                    <a href="{{ route('entreprise.dashboard') }}" class="btn-secondary text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour au dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
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

    // Gestion de l'upload de fichier
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const label = document.querySelector('.file-upload-label');
        
        if (file) {
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            label.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                    <p class="text-sm text-gray-600">${fileName}</p>
                    <p class="text-xs text-gray-400">${fileSize} MB</p>
                </div>
            `;
            label.style.borderColor = '#10b981';
            label.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
        }
    });

    // Validation du formulaire
    document.getElementById('entrepriseForm').addEventListener('submit', function(e) {
        const requiredFields = ['nom', 'secteur', 'taille', 'ville'];
        let isValid = true;

        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Validation en temps réel
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endpush
