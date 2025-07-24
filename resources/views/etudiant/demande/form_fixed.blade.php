@extends('layouts.app')

@section('title', 'Demande de stage ' . ucfirst($type))

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
        padding: 1.5rem;
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

    .radio-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .radio-option {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--background);
    }

    .radio-option:hover {
        border-color: var(--primary-blue);
        background: var(--secondary-blue);
    }

    .radio-option input[type="radio"] {
        margin-right: 0.5rem;
    }

    .radio-option.active {
        border-color: var(--primary-blue);
        background: rgba(37, 99, 235, 0.1);
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

    /* Section binôme - CSS amélioré */
    .binome-section {
        background: var(--background-alt);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1rem;
        display: none !important; /* Force le masquage par défaut */
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }

    .binome-section.show {
        display: block !important; /* Force l'affichage */
        opacity: 1;
        transform: translateY(0);
    }

    /* Animation pour une meilleure UX */
    .binome-section.show {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1rem;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .radio-group {
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
                <i class="fas fa-{{ $type === 'academique' ? 'graduation-cap' : 'briefcase' }} text-4xl mb-4"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Demande de Stage {{ ucfirst($type) }}</h1>
            <p class="text-white text-sm opacity-90">{{ $entreprise->nom }} - {{ $entreprise->secteur ?? 'Secteur non spécifié' }}</p>
        </div>

        <!-- Formulaire -->
        <div class="form-body">
            <form method="POST" action="{{ route('demandes.store') }}" enctype="multipart/form-data" id="demandeForm">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="entreprise_id" value="{{ $entreprise->id }}">

                <!-- Informations générales -->
                <div class="form-section" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Informations générales
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="objet" class="form-label">Objet de la demande *</label>
                            <input id="objet" name="objet" type="text" value="{{ old('objet', 'Demande de stage ' . $type) }}" 
                                   placeholder="Ex: Demande de stage en développement web" 
                                   class="form-control @error('objet') is-invalid @enderror" required>
                            @error('objet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_debut_souhaitee" class="form-label">Date de début souhaitée *</label>
                            <input id="date_debut_souhaitee" name="date_debut_souhaitee" type="date" 
                                   value="{{ old('date_debut_souhaitee') }}" 
                                   class="form-control @error('date_debut_souhaitee') is-invalid @enderror" required>
                            @error('date_debut_souhaitee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="date_fin_souhaitee" class="form-label">Date de fin souhaitée *</label>
                        <input id="date_fin_souhaitee" name="date_fin_souhaitee" type="date" 
                               value="{{ old('date_fin_souhaitee') }}" 
                               class="form-control @error('date_fin_souhaitee') is-invalid @enderror" required>
                        @error('date_fin_souhaitee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if($type === 'academique')
                    <!-- Spécifique au stage académique -->
                    <div class="form-section" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="section-title">
                            <i class="fas fa-graduation-cap text-blue-500"></i>
                            Informations académiques
                        </h3>
                        
                        <div class="form-group">
                            <label class="form-label">Mode de stage *</label>
                            <div class="radio-group">
                                <label class="radio-option" id="radio-solo">
                                    <input type="radio" name="mode" value="solo" {{ old('mode') === 'solo' ? 'checked' : '' }} required>
                                    <span>Stage individuel</span>
                                </label>
                                <label class="radio-option" id="radio-binome">
                                    <input type="radio" name="mode" value="binome" {{ old('mode') === 'binome' ? 'checked' : '' }} required>
                                    <span>Stage en binôme</span>
                                </label>
                            </div>
                            @error('mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section binôme avec ID unique -->
                        <div class="binome-section" id="binome-section">
                            <!-- Message d'information amélioré -->
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <strong>Important :</strong> Votre binôme doit d'abord avoir un compte dans le système. 
                                            Assurez-vous qu'il/elle s'est déjà inscrit(e) avec l'email et le nom que vous allez renseigner ci-dessous.
                                        </p>
                                        <p class="text-xs text-blue-600 mt-2">
                                            💡 Si votre binôme n'a pas encore de compte, demandez-lui de s'inscrire d'abord sur la plateforme.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="email_binome" class="form-label">Email du binôme *</label>
                                    <input id="email_binome" name="email_binome" type="email" 
                                           value="{{ old('email_binome') }}" 
                                           placeholder="email@exemple.com" 
                                           class="form-control @error('email_binome') is-invalid @enderror">
                                    @error('email_binome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-gray-500 text-xs mt-1">Email utilisé lors de l'inscription de votre binôme</small>
                                </div>

                                <div class="form-group">
                                    <label for="nom_binome" class="form-label">Nom complet du binôme *</label>
                                    <input id="nom_binome" name="nom_binome" type="text" 
                                           value="{{ old('nom_binome') }}" 
                                           placeholder="Prénom Nom" 
                                           class="form-control @error('nom_binome') is-invalid @enderror">
                                    @error('nom_binome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-gray-500 text-xs mt-1">Nom utilisé lors de l'inscription de votre binôme</small>
                                </div>
                            </div>

                            <!-- Bouton de vérification amélioré -->
                            <div class="mt-4">
                                <button type="button" id="verifier-binome" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-search mr-2"></i>
                                    Vérifier l'existence du binôme
                                </button>
                                <div id="verification-result" class="mt-2 text-sm"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="periode" class="form-label">Période académique *</label>
                                <input id="periode" name="periode" type="text" 
                                       value="{{ old('periode') }}" 
                                       placeholder="Ex: Semestre 6 - 2024/2025" 
                                       class="form-control @error('periode') is-invalid @enderror" required>
                                @error('periode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="objectifs_stage" class="form-label">Objectifs du stage *</label>
                            <textarea id="objectifs_stage" name="objectifs_stage" rows="4" 
                                      placeholder="Décrivez vos objectifs d'apprentissage et les compétences que vous souhaitez développer..." 
                                      class="form-control @error('objectifs_stage') is-invalid @enderror" required>{{ old('objectifs_stage') }}</textarea>
                            @error('objectifs_stage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                @if($type === 'professionnel')
                    <!-- Spécifique au stage professionnel -->
                    <div class="form-section" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="section-title">
                            <i class="fas fa-briefcase text-blue-500"></i>
                            Informations professionnelles
                        </h3>
                        
                        <div class="form-group">
                            <label for="date_debut_disponible" class="form-label">Date de disponibilité *</label>
                            <input id="date_debut_disponible" name="date_debut_disponible" type="date" 
                                   value="{{ old('date_debut_disponible') }}" 
                                   class="form-control @error('date_debut_disponible') is-invalid @enderror" required>
                            @error('date_debut_disponible')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <!-- Documents requis -->
                <div class="form-section" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="section-title">
                        <i class="fas fa-file-upload text-blue-500"></i>
                        Documents requis
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- CV -->
                        <div class="form-group">
                            <label for="cv" class="form-label">CV * (PDF uniquement)</label>
                            <div class="file-upload">
                                <input type="file" id="cv" name="cv" accept=".pdf" required 
                                       class="@error('cv') is-invalid @enderror">
                                <label for="cv" class="file-upload-label">
                                    <div class="text-center">
                                        <i class="fas fa-file-pdf text-2xl text-red-500 mb-2"></i>
                                        <p class="text-sm text-gray-600">Cliquez pour sélectionner votre CV</p>
                                        <p class="text-xs text-gray-400">PDF jusqu'à 5MB</p>
                                    </div>
                                </label>
                            </div>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lettre de motivation -->
                        <div class="form-group">
                            <label for="lettre_motivation" class="form-label">Lettre de motivation * (PDF/DOCX)</label>
                            <div class="file-upload">
                                <input type="file" id="lettre_motivation" name="lettre_motivation" 
                                       accept=".pdf,.docx" required class="@error('lettre_motivation') is-invalid @enderror">
                                <label for="lettre_motivation" class="file-upload-label">
                                    <div class="text-center">
                                        <i class="fas fa-file-alt text-2xl text-blue-500 mb-2"></i>
                                        <p class="text-sm text-gray-600">Lettre de motivation</p>
                                        <p class="text-xs text-gray-400">PDF/DOCX jusqu'à 5MB</p>
                                    </div>
                                </label>
                            </div>
                            @error('lettre_motivation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pièce d'identité -->
                        <div class="form-group">
                            <label for="piece_identite" class="form-label">Pièce d'identité * (PDF/JPG/PNG)</label>
                            <div class="file-upload">
                                <input type="file" id="piece_identite" name="piece_identite" 
                                       accept=".pdf,.jpg,.jpeg,.png" required class="@error('piece_identite') is-invalid @enderror">
                                <label for="piece_identite" class="file-upload-label">
                                    <div class="text-center">
                                        <i class="fas fa-id-card text-2xl text-green-500 mb-2"></i>
                                        <p class="text-sm text-gray-600">Pièce d'identité</p>
                                        <p class="text-xs text-gray-400">PDF/Image jusqu'à 5MB</p>
                                    </div>
                                </label>
                            </div>
                            @error('piece_identite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($type === 'academique')
                            <!-- Lettre de recommandation (obligatoire pour académique) -->
                            <div class="form-group">
                                <label for="recommandation" class="form-label">Lettre de recommandation * (PDF/DOCX)</label>
                                <div class="file-upload">
                                    <input type="file" id="recommandation" name="recommandation" 
                                           accept=".pdf,.docx" required class="@error('recommandation') is-invalid @enderror">
                                    <label for="recommandation" class="file-upload-label">
                                        <div class="text-center">
                                            <i class="fas fa-certificate text-2xl text-yellow-500 mb-2"></i>
                                            <p class="text-sm text-gray-600">Lettre de recommandation</p>
                                            <p class="text-xs text-gray-400">PDF/DOCX jusqu'à 5MB</p>
                                        </div>
                                    </label>
                                </div>
                                @error('recommandation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <!-- Lettre de recommandation (optionnelle pour professionnel) -->
                            <div class="form-group">
                                <label for="recommandation" class="form-label">Lettre de recommandation (optionnel)</label>
                                <div class="file-upload">
                                    <input type="file" id="recommandation" name="recommandation" 
                                           accept=".pdf,.docx" class="@error('recommandation') is-invalid @enderror">
                                    <label for="recommandation" class="file-upload-label">
                                        <div class="text-center">
                                            <i class="fas fa-certificate text-2xl text-yellow-500 mb-2"></i>
                                            <p class="text-sm text-gray-600">Lettre de recommandation</p>
                                            <p class="text-xs text-gray-400">PDF/DOCX jusqu'à 5MB</p>
                                        </div>
                                    </label>
                                </div>
                                @error('recommandation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Portfolio (optionnel) -->
                    <div class="form-group">
                        <label for="portfolio" class="form-label">Portfolio (optionnel)</label>
                        <div class="file-upload">
                            <input type="file" id="portfolio" name="portfolio" 
                                   accept=".pdf,.zip,.rar" class="@error('portfolio') is-invalid @enderror">
                            <label for="portfolio" class="file-upload-label">
                                <div class="text-center">
                                    <i class="fas fa-folder-open text-2xl text-purple-500 mb-2"></i>
                                    <p class="text-sm text-gray-600">Portfolio ou projets réalisés</p>
                                    <p class="text-xs text-gray-400">PDF/ZIP/RAR jusqu'à 10MB</p>
                                </div>
                            </label>
                        </div>
                        @error('portfolio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8" data-aos="fade-up" data-aos-delay="400">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer la demande
                    </button>
                    <a href="{{ route('demande.stage.choix', ['entreprise_id' => $entreprise->id]) }}" 
                       class="btn-secondary text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
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

    // Attendre que le DOM soit complètement chargé
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Script de gestion du binôme initialisé');

        // Gestion du mode binôme - Version améliorée avec debug
        const modeRadios = document.querySelectorAll('input[name="mode"]');
        const binomeSection = document.getElementById('binome-section');
        const emailBinome = document.getElementById('email_binome');
        const nomBinome = document.getElementById('nom_binome');

        console.log('📋 Éléments trouvés:', {
            modeRadios: modeRadios.length,
            binomeSection: !!binomeSection,
            emailBinome: !!emailBinome,
            nomBinome: !!nomBinome
        });

        if (modeRadios.length > 0 && binomeSection) {
            modeRadios.forEach((radio, index) => {
                console.log(`📻 Radio ${index + 1}: ${radio.value}`);
                
                radio.addEventListener('change', function() {
                    console.log(`🔄 Changement de mode: ${this.value}`);
                    
                    if (this.value === 'binome') {
                        console.log('👥 Affichage de la section binôme');
                        binomeSection.classList.add('show');
                        
                        if (emailBinome) {
                            emailBinome.setAttribute('required', 'required');
                            console.log('✅ Email binôme requis');
                        }
                        if (nomBinome) {
                            nomBinome.setAttribute('required', 'required');
                            console.log('✅ Nom binôme requis');
                        }
                    } else {
                        console.log('👤 Masquage de la section binôme');
                        binomeSection.classList.remove('show');
                        
                        if (emailBinome) {
                            emailBinome.removeAttribute('required');
                            emailBinome.value = '';
                            console.log('❌ Email binôme non requis et vidé');
                        }
                        if (nomBinome) {
                            nomBinome.removeAttribute('required');
                            nomBinome.value = '';
                            console.log('❌ Nom binôme non requis et vidé');
                        }
