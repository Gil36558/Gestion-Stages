@extends('layouts.app')

@section('title', 'Modifier le profil entreprise')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-blue: #2563eb;
        --primary-dark: #1e40af;
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
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .form-card {
        background: var(--background);
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .form-file {
        padding: 0.5rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary-blue);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
    }

    .btn-secondary {
        background: var(--background-alt);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    .error-message {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .current-logo {
        max-width: 100px;
        max-height: 100px;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-custom" data-aos="fade-up">
    <div class="form-card">
        <div class="form-header">
            <h1><i class="fas fa-edit text-blue-600"></i> Modifier le profil entreprise</h1>
            <p>Mettez à jour les informations de votre entreprise</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-400 mr-2 mt-1"></i>
                    <div>
                        <h3 class="text-red-800 font-semibold">Erreurs de validation :</h3>
                        <ul class="text-red-700 mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('entreprise.update', $entreprise) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom de l'entreprise -->
                <div class="form-group">
                    <label for="nom" class="form-label">
                        <i class="fas fa-building text-blue-600 mr-2"></i>Nom de l'entreprise *
                    </label>
                    <input type="text" 
                           id="nom" 
                           name="nom" 
                           class="form-input" 
                           value="{{ old('nom', $entreprise->nom) }}" 
                           required>
                    @error('nom')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Secteur d'activité -->
                <div class="form-group">
                    <label for="secteur" class="form-label">
                        <i class="fas fa-industry text-green-600 mr-2"></i>Secteur d'activité *
                    </label>
                    <input type="text" 
                           id="secteur" 
                           name="secteur" 
                           class="form-input" 
                           value="{{ old('secteur', $entreprise->secteur) }}" 
                           placeholder="Ex: Technologie, Finance, Santé..."
                           required>
                    @error('secteur')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Taille de l'entreprise -->
                <div class="form-group">
                    <label for="taille" class="form-label">
                        <i class="fas fa-users text-purple-600 mr-2"></i>Taille de l'entreprise *
                    </label>
                    <select id="taille" name="taille" class="form-input form-select" required>
                        <option value="">Sélectionnez la taille</option>
                        <option value="petite" {{ old('taille', $entreprise->taille) == 'petite' ? 'selected' : '' }}>
                            Petite (1-50 employés)
                        </option>
                        <option value="moyenne" {{ old('taille', $entreprise->taille) == 'moyenne' ? 'selected' : '' }}>
                            Moyenne (51-250 employés)
                        </option>
                        <option value="grande" {{ old('taille', $entreprise->taille) == 'grande' ? 'selected' : '' }}>
                            Grande (250+ employés)
                        </option>
                    </select>
                    @error('taille')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ville -->
                <div class="form-group">
                    <label for="ville" class="form-label">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>Ville
                    </label>
                    <input type="text" 
                           id="ville" 
                           name="ville" 
                           class="form-input" 
                           value="{{ old('ville', $entreprise->ville) }}" 
                           placeholder="Ex: Paris, Lyon, Marseille...">
                    @error('ville')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Site web -->
            <div class="form-group">
                <label for="site_web" class="form-label">
                    <i class="fas fa-globe text-blue-600 mr-2"></i>Site web
                </label>
                <input type="url" 
                       id="site_web" 
                       name="site_web" 
                       class="form-input" 
                       value="{{ old('site_web', $entreprise->site_web) }}" 
                       placeholder="https://www.exemple.com">
                @error('site_web')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left text-gray-600 mr-2"></i>Description de l'entreprise
                </label>
                <textarea id="description" 
                          name="description" 
                          class="form-input form-textarea" 
                          placeholder="Décrivez votre entreprise, ses activités, sa mission...">{{ old('description', $entreprise->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Logo -->
            <div class="form-group">
                <label for="logo" class="form-label">
                    <i class="fas fa-image text-yellow-600 mr-2"></i>Logo de l'entreprise
                </label>
                
                @if($entreprise->logo)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Logo actuel :</p>
                        <img src="{{ Storage::url($entreprise->logo) }}" 
                             alt="Logo {{ $entreprise->nom }}" 
                             class="current-logo">
                    </div>
                @endif
                
                <input type="file" 
                       id="logo" 
                       name="logo" 
                       class="form-input form-file" 
                       accept="image/*">
                <p class="text-sm text-gray-500 mt-1">
                    Formats acceptés : JPG, PNG, GIF. Taille max : 2MB
                </p>
                @error('logo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour le profil
                </button>
                <a href="{{ route('entreprise.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
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
        duration: 600,
        easing: 'ease-out-cubic',
    });

    // Prévisualisation du logo
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Créer ou mettre à jour l'aperçu
                let preview = document.getElementById('logo-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'logo-preview';
                    preview.className = 'current-logo mt-2';
                    e.target.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
