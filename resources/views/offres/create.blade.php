@extends('layouts.app')

@section('title', 'Publier une offre de stage')

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
        --success-color: #10b981;
    }

    .form-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
        display: flex;
        justify-content: center;
    }

    .form-card {
        background: var(--background);
        border-radius: 1.5rem;
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
        max-width: 1000px;
        overflow: hidden;
        margin: 2rem 0;
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

    .form-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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

    .checkbox-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .checkbox-option {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--background);
    }

    .checkbox-option:hover {
        border-color: var(--primary-blue);
        background: var(--secondary-blue);
    }

    .checkbox-option input[type="checkbox"] {
        margin-right: 0.5rem;
    }

    .checkbox-option.active {
        border-color: var(--primary-blue);
        background: rgba(37, 99, 235, 0.1);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1rem;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .checkbox-group {
            flex-direction: column;
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
                <i class="fas fa-briefcase text-4xl mb-4"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Publier une offre de stage</h1>
            <p class="text-white text-sm opacity-90">{{ $entreprise->nom }} - Attirez les meilleurs talents</p>
        </div>

        <!-- Formulaire -->
        <div class="form-body">
            <form method="POST" action="{{ route('entreprise.offres.store') }}" id="offreForm">
                @csrf

                <!-- Informations de base -->
                <div class="form-section" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Informations générales
                    </h3>
                    
                    <div class="form-group">
                        <label for="titre" class="form-label">Titre du poste *</label>
                        <input id="titre" name="titre" type="text" value="{{ old('titre') }}" 
                               placeholder="Ex: Stage Développeur Web, Assistant Marketing..." 
                               class="form-control @error('titre') is-invalid @enderror" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description du poste *</label>
                        <textarea id="description" name="description" rows="6" 
                                  placeholder="Décrivez les missions, responsabilités, environnement de travail..." 
                                  class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-gray-500 text-xs mt-1">Minimum 50 caractères pour une description complète</small>
                    </div>

                    <div class="form-group">
                        <label for="competences_requises" class="form-label">Compétences requises</label>
                        <textarea id="competences_requises" name="competences_requises" rows="3" 
                                  placeholder="Ex: HTML/CSS, JavaScript, Communication, Travail en équipe..." 
                                  class="form-control @error('competences_requises') is-invalid @enderror">{{ old('competences_requises') }}</textarea>
                        @error('competences_requises')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Type et conditions -->
                <div class="form-section" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="section-title">
                        <i class="fas fa-cogs text-blue-500"></i>
                        Type et conditions
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="type_stage" class="form-label">Type de stage accepté *</label>
                            <select id="type_stage" name="type_stage" class="form-control @error('type_stage') is-invalid @enderror" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="academique" {{ old('type_stage') == 'academique' ? 'selected' : '' }}>Stage académique uniquement</option>
                                <option value="professionnel" {{ old('type_stage') == 'professionnel' ? 'selected' : '' }}>Stage professionnel uniquement</option>
                                <option value="les_deux" {{ old('type_stage') == 'les_deux' ? 'selected' : '' }}>Les deux types</option>
                            </select>
                            @error('type_stage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="duree" class="form-label">Durée du stage</label>
                            <input id="duree" name="duree" type="text" value="{{ old('duree') }}" 
                                   placeholder="Ex: 3 mois, 6 mois, Flexible..." 
                                   class="form-control @error('duree') is-invalid @enderror">
                            @error('duree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="remuneration" class="form-label">Rémunération</label>
                            <input id="remuneration" name="remuneration" type="text" value="{{ old('remuneration') }}" 
                                   placeholder="Ex: 500€/mois, Non rémunéré, À négocier..." 
                                   class="form-control @error('remuneration') is-invalid @enderror">
                            @error('remuneration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="lieu" class="form-label">Lieu du stage</label>
                            <input id="lieu" name="lieu" type="text" value="{{ old('lieu', $entreprise->adresse) }}" 
                                   placeholder="Ex: Paris, Télétravail, Hybride..." 
                                   class="form-control @error('lieu') is-invalid @enderror">
                            @error('lieu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="niveau_etudes" class="form-label">Niveau d'études requis</label>
                            <select id="niveau_etudes" name="niveau_etudes" class="form-control @error('niveau_etudes') is-invalid @enderror">
                                <option value="">Tous niveaux</option>
                                <option value="Bac+1" {{ old('niveau_etudes') == 'Bac+1' ? 'selected' : '' }}>Bac+1</option>
                                <option value="Bac+2" {{ old('niveau_etudes') == 'Bac+2' ? 'selected' : '' }}>Bac+2</option>
                                <option value="Bac+3" {{ old('niveau_etudes') == 'Bac+3' ? 'selected' : '' }}>Bac+3 (Licence)</option>
                                <option value="Bac+4" {{ old('niveau_etudes') == 'Bac+4' ? 'selected' : '' }}>Bac+4 (Master 1)</option>
                                <option value="Bac+5" {{ old('niveau_etudes') == 'Bac+5' ? 'selected' : '' }}>Bac+5 (Master 2)</option>
                                <option value="Bac+6+" {{ old('niveau_etudes') == 'Bac+6+' ? 'selected' : '' }}>Bac+6 et plus</option>
                            </select>
                            @error('niveau_etudes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nombre_postes" class="form-label">Nombre de postes</label>
                            <input id="nombre_postes" name="nombre_postes" type="number" min="1" max="50" 
                                   value="{{ old('nombre_postes', 1) }}" 
                                   class="form-control @error('nombre_postes') is-invalid @enderror">
                            @error('nombre_postes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="form-section" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-alt text-blue-500"></i>
                        Dates importantes
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label for="date_debut" class="form-label">Date de début souhaitée</label>
                            <input id="date_debut" name="date_debut" type="date" 
                                   value="{{ old('date_debut') }}" 
                                   class="form-control @error('date_debut') is-invalid @enderror">
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_fin" class="form-label">Date de fin souhaitée</label>
                            <input id="date_fin" name="date_fin" type="date" 
                                   value="{{ old('date_fin') }}" 
                                   class="form-control @error('date_fin') is-invalid @enderror">
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_limite_candidature" class="form-label">Date limite de candidature</label>
                            <input id="date_limite_candidature" name="date_limite_candidature" type="date" 
                                   value="{{ old('date_limite_candidature') }}" 
                                   class="form-control @error('date_limite_candidature') is-invalid @enderror">
                            @error('date_limite_candidature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="form-section" data-aos="fade-up" data-aos-delay="400">
                    <h3 class="section-title">
                        <i class="fas fa-toggle-on text-blue-500"></i>
                        Publication
                    </h3>
                    
                    <div class="form-group">
                        <label for="statut" class="form-label">Statut de l'offre</label>
                        <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror">
                            <option value="active" {{ old('statut', 'active') == 'active' ? 'selected' : '' }}>Active (visible par les étudiants)</option>
                            <option value="inactive" {{ old('statut') == 'inactive' ? 'selected' : '' }}>Brouillon (non visible)</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-gray-500 text-xs mt-1">Vous pourrez modifier le statut plus tard</small>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8" data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Publier l'offre
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

    // Validation des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const dateLimite = document.getElementById('date_limite_candidature');

    if (dateDebut && dateFin) {
        dateDebut.addEventListener('change', function() {
            dateFin.min = this.value;
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
            }
        });
    }

    // Validation du formulaire
    document.getElementById('offreForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validation spéciale pour la description (minimum 50 caractères)
        const description = document.getElementById('description');
        if (description.value.trim().length < 50) {
            description.classList.add('is-invalid');
            isValid = false;
        }

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
            } else if (this.id === 'description' && this.value.trim().length < 50) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Compteur de caractères pour la description
    const description = document.getElementById('description');
    const descriptionGroup = description.closest('.form-group');
    
    // Créer un compteur
    const counter = document.createElement('small');
    counter.className = 'text-gray-500 text-xs mt-1 block';
    counter.textContent = '0 / 50 caractères minimum';
    descriptionGroup.appendChild(counter);

    description.addEventListener('input', function() {
        const length = this.value.length;
        counter.textContent = `${length} / 50 caractères minimum`;
        
        if (length >= 50) {
            counter.style.color = '#10b981';
        } else {
            counter.style.color = '#6b7280';
        }
    });
</script>
@endpush
