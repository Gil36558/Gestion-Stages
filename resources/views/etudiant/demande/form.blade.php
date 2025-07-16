@extends('layouts.app')

@section('title', 'Formulaire de demande de stage')

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
        --danger-color: #ef4444;
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

    .form-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 1rem;
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        overflow-y: auto;
    }

    .form-card {
        background: var(--background);
        border-radius: 1.5rem;
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
        max-width: 800px;
        padding: 2rem;
        margin: 2rem 0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .form-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-title span {
        color: var(--primary-blue);
        text-transform: uppercase;
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

    .form-info {
        background: var(--background-alt);
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .form-section {
        background: var(--background-alt);
        padding: 1.5rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-blue);
    }

    .form-section h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .form-section h3 i {
        margin-right: 0.5rem;
        color: var(--primary-blue);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: ' *';
        color: var(--danger-color);
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 1rem;
        color: var(--text-primary);
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-display {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border: 2px dashed var(--border-color);
        border-radius: 0.5rem;
        background: var(--background-alt);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-input-display:hover {
        border-color: var(--primary-blue);
        background: var(--secondary-blue);
    }

    .file-input-display i {
        margin-right: 0.5rem;
        color: var(--primary-blue);
    }

    .binome-fields {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed var(--border-color);
        display: none;
    }

    .binome-fields.active {
        display: block;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .checkbox-group input {
        margin-right: 0.5rem;
    }

    .radio-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .radio-group label {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-weight: 500;
    }

    .radio-group input {
        margin-right: 0.5rem;
    }

    .error-message {
        font-size: 0.75rem;
        color: var(--danger-color);
        margin-top: 0.25rem;
    }

    .submit-btn {
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
        text-align: center;
        border: none;
        cursor: pointer;
    }

    .submit-btn.primary {
        background: var(--primary-blue);
        color: white;
    }

    .submit-btn.primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .info-box {
        background: #e0f2fe;
        border: 1px solid #0288d1;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #01579b;
    }

    .info-box i {
        margin-right: 0.5rem;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1rem;
        }
        .form-card {
            padding: 1.5rem;
        }
        .form-title {
            font-size: 1.75rem;
        }
        .form-row,
        .form-row-3 {
            grid-template-columns: 1fr;
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
        <h1 class="form-title">
            Demande de stage : 
            <span>{{ $type === 'academique' ? 'Académique' : 'Professionnel' }}</span>
        </h1>

        <!-- Rappel de l'entreprise ciblée -->
        <div class="form-info" data-aos="fade-up" data-aos-delay="100">
            <p><strong>Entreprise :</strong> {{ $entreprise->nom }}</p>
            @if($type === 'professionnel')
                <p><strong>Adresse :</strong> {{ $entreprise->ville ?? 'Non spécifiée' }}</p>
                <p><strong>Contact :</strong> {{ $entreprise->email ?? 'Non spécifié' }}</p>
            @endif
        </div>

        <!-- Formulaire unique -->
        <form action="{{ route('demandes.store') }}" method="POST" enctype="multipart/form-data" data-aos="fade-up" data-aos-delay="200">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="entreprise_id" value="{{ $entreprise->id }}">

            <!-- Section Informations Personnelles -->
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Informations Personnelles</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Portfolio ou réalisations</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="portfolio" accept=".pdf,.zip,.rar" class="file-input">
                            <div class="file-input-display">
                                <i class="fas fa-upload"></i>
                                <span>Choisir le fichier (PDF, ZIP, RAR)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-input" required 
                               value="{{ old('email', auth()->user()->email ?? '') }}" 
                               placeholder="votre.email@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Téléphone</label>
                        <input type="tel" name="telephone" class="form-input" required 
                               value="{{ old('telephone') }}" 
                               placeholder="ex: +229 XX XX XX XX">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Téléphone WhatsApp</label>
                        <input type="tel" name="whatsapp" class="form-input" 
                               value="{{ old('whatsapp') }}" 
                               placeholder="ex: +229 XX XX XX XX">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Adresse complète</label>
                        <textarea name="adresse" rows="2" class="form-textarea" required 
                                  placeholder="Quartier, rue, numéro, ville, département">{{ old('adresse') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section Formation -->
            <div class="form-section">
                <h3><i class="fas fa-graduation-cap"></i> Formation</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Établissement</label>
                        <input type="text" name="etablissement" class="form-input" required 
                               value="{{ old('etablissement') }}" 
                               placeholder="ex: Université d'Abomey-Calavi">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Faculté/École</label>
                        <input type="text" name="faculte" class="form-input" required 
                               value="{{ old('faculte') }}" 
                               placeholder="ex: FASEG, EPAC, ENEAM">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Filière/Spécialité</label>
                        <input type="text" name="filiere" class="form-input" required 
                               value="{{ old('filiere') }}" 
                               placeholder="ex: Informatique, Gestion, Économie">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Niveau d'études</label>
                        <select name="niveau_etudes" class="form-select" required>
                            <option value="">Sélectionnez votre niveau</option>
                            <option value="licence_1" {{ old('niveau_etudes') == 'licence_1' ? 'selected' : '' }}>Licence 1</option>
                            <option value="licence_2" {{ old('niveau_etudes') == 'licence_2' ? 'selected' : '' }}>Licence 2</option>
                            <option value="licence_3" {{ old('niveau_etudes') == 'licence_3' ? 'selected' : '' }}>Licence 3</option>
                            <option value="master_1" {{ old('niveau_etudes') == 'master_1' ? 'selected' : '' }}>Master 1</option>
                            <option value="master_2" {{ old('niveau_etudes') == 'master_2' ? 'selected' : '' }}>Master 2</option>
                            <option value="doctorat" {{ old('niveau_etudes') == 'doctorat' ? 'selected' : '' }}>Doctorat</option>
                            <option value="bts" {{ old('niveau_etudes') == 'bts' ? 'selected' : '' }}>BTS</option>
                            <option value="dut" {{ old('niveau_etudes') == 'dut' ? 'selected' : '' }}>DUT</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Année académique</label>
                        <input type="text" name="annee_academique" class="form-input" required 
                               value="{{ old('annee_academique') }}" 
                               placeholder="ex: 2024-2025">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Numéro matricule</label>
                        <input type="text" name="matricule" class="form-input" 
                               value="{{ old('matricule') }}" 
                               placeholder="Numéro d'étudiant">
                    </div>
                </div>
            </div>

            <!-- Section Pièces d'identité -->
            <div class="form-section">
                <h3><i class="fas fa-id-card"></i> Pièces d'identité</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Type de pièce d'identité</label>
                        <select name="type_piece_identite" class="form-select" required>
                            <option value="">Sélectionnez le type</option>
                            <option value="cni" {{ old('type_piece_identite') == 'cni' ? 'selected' : '' }}>Carte Nationale d'Identité</option>
                            <option value="passeport" {{ old('type_piece_identite') == 'passeport' ? 'selected' : '' }}>Passeport</option>
                            <option value="permis_conduire" {{ old('type_piece_identite') == 'permis_conduire' ? 'selected' : '' }}>Permis de conduire</option>
                            <option value="carte_consulaire" {{ old('type_piece_identite') == 'carte_consulaire' ? 'selected' : '' }}>Carte consulaire</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Numéro de la pièce</label>
                        <input type="text" name="numero_piece_identite" class="form-input" required 
                               value="{{ old('numero_piece_identite') }}" 
                               placeholder="Numéro de la pièce d'identité">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Copie de la pièce d'identité</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="piece_identite" accept=".pdf,.jpg,.jpeg,.png" class="file-input" required>
                        <div class="file-input-display">
                            <i class="fas fa-upload"></i>
                            <span>Choisir le fichier (PDF, JPG, PNG)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Informations complémentaires -->
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Informations complémentaires</h3>
                
                <div class="form-group">
                    <label class="form-label">Langues parlées</label>
                    <div class="form-row-3">
                        <div>
                            <label class="form-label">Français</label>
                            <select name="niveau_francais" class="form-select">
                                <option value="">Niveau</option>
                                <option value="debutant">Débutant</option>
                                <option value="intermediaire">Intermédiaire</option>
                                <option value="avance">Avancé</option>
                                <option value="bilingue">Bilingue</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Anglais</label>
                            <select name="niveau_anglais" class="form-select">
                                <option value="">Niveau</option>
                                <option value="debutant">Débutant</option>
                                <option value="intermediaire">Intermédiaire</option>
                                <option value="avance">Avancé</option>
                                <option value="bilingue">Bilingue</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Autres langues</label>
                            <input type="text" name="autres_langues" class="form-input" 
                                   placeholder="ex: Fon, Yoruba, Allemand">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraintes particulières</label>
                    <textarea name="contraintes" rows="3" class="form-textarea" 
                              placeholder="Horaires, santé, famille, etc."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Attentes particulières</label>
                    <textarea name="attentes" rows="3" class="form-textarea" 
                              placeholder="Ce que vous attendez spécifiquement de ce stage..."></textarea>
                </div>
            </div>

            <!-- Section Personne de contact d'urgence -->
            <div class="form-section">
                <h3><i class="fas fa-phone"></i> Contact d'urgence</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Nom complet</label>
                        <input type="text" name="contact_urgence_nom" class="form-input" required 
                               placeholder="Nom de la personne à contacter">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Lien de parenté</label>
                        <select name="contact_urgence_lien" class="form-select" required>
                            <option value="">Sélectionnez</option>
                            <option value="pere">Père</option>
                            <option value="mere">Mère</option>
                            <option value="tuteur">Tuteur/Tutrice</option>
                            <option value="frere_soeur">Frère/Sœur</option>
                            <option value="oncle_tante">Oncle/Tante</option>
                            <option value="ami_proche">Ami(e) proche</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Téléphone</label>
                        <input type="tel" name="contact_urgence_telephone" class="form-input" required 
                               placeholder="+229 XX XX XX XX">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="contact_urgence_email" class="form-input" 
                               placeholder="email@example.com">
                    </div>
                </div>
            </div>

            <!-- Section Déclaration et engagement -->
            <div class="form-section">
                <h3><i class="fas fa-check-circle"></i> Déclaration et engagement</h3>
                
                <div class="checkbox-group">
                    <input type="checkbox" name="declaration_veracite" id="declaration_veracite" required>
                    <label for="declaration_veracite" class="form-label">
                        Je déclare sur l'honneur que toutes les informations fournies sont exactes et véridiques.
                    </label>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="engagement_respect" id="engagement_respect" required>
                    <label for="engagement_respect" class="form-label">
                        Je m'engage à respecter le règlement intérieur de l'entreprise et à faire preuve d'assiduité et de professionnalisme.
                    </label>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="autorisation_donnees" id="autorisation_donnees" required>
                    <label for="autorisation_donnees" class="form-label">
                        J'autorise l'utilisation de mes données personnelles dans le cadre de cette demande de stage.
                    </label>
                </div>

                @if($type === 'academique')
                <div class="checkbox-group">
                    <input type="checkbox" name="autorisation_evaluation" id="autorisation_evaluation">
                    <label for="autorisation_evaluation" class="form-label">
                        J'autorise l'entreprise à communiquer avec mon établissement pour l'évaluation de mon stage.
                    </label>
                </div>
                @endif

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note importante :</strong> Cette demande sera traitée dans un délai de 7 à 14 jours ouvrables. 
                    Vous recevrez une notification par email concernant le statut de votre demande.
                </div>
            </div>

            <!-- Bloc stage académique -->
            @if($type === 'academique')
                <div class="form-section">
                    <h3><i class="fas fa-university"></i> Détails du stage académique</h3>
                    
                    <div class="form-group">
                        <label class="form-label required">Mode de stage</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="mode" value="solo" checked>
                                <span>En solo</span>
                            </label>
                            <label>
                                <input type="radio" name="mode" value="binome">
                                <span>En binôme</span>
                            </label>
                        </div>
                    </div>

                    <div id="binome-fields" class="binome-fields">
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            Votre binôme doit avoir un compte sur la plateforme et sera invité à confirmer sa participation.
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email de votre binôme</label>
                                <input type="email" name="email_binome" class="form-input" 
                                       placeholder="binome@example.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nom du binôme</label>
                                <input type="text" name="nom_binome" class="form-input" 
                                       placeholder="Nom complet du binôme">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Objet de la demande</label>
                        <input type="text" name="objet" class="form-input" required placeholder="ex: Demande de stage académique">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Date de début souhaitée</label>
                            <input type="date" name="date_debut_souhaitee" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label required">Date de fin souhaitée</label>
                            <input type="date" name="date_fin_souhaitee" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Période souhaitée (description)</label>
                        <input type="text" name="periode" class="form-input" required 
                               placeholder="ex: Janvier à Mars 2025, Semestre 1">
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Objectifs du stage</label>
                        <textarea name="objectifs_stage" rows="3" class="form-textarea" required 
                                  placeholder="Décrivez vos objectifs pédagogiques et professionnels..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Lettre de motivation</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="lettre_motivation" accept=".pdf,.docx" class="file-input" required>
                            <div class="file-input-display">
                                <i class="fas fa-upload"></i>
                                <span>Choisir le fichier (PDF, DOCX)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Compétences à développer</label>
                        <textarea name="competences_a_developper" rows="3" class="form-textarea" 
                                  placeholder="Quelles compétences souhaitez-vous développer ?"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Lettre de recommandation</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="recommandation" accept=".pdf,.docx" class="file-input" required>
                            <div class="file-input-display">
                                <i class="fas fa-upload"></i>
                                <span>Choisir le fichier (PDF, DOCX)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">CV</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="cv" accept=".pdf" class="file-input" required>
                            <div class="file-input-display">
                                <i class="fas fa-upload"></i>
                                <span>Choisir le fichier CV (PDF uniquement)</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bloc stage professionnel -->
            @if($type === 'professionnel')
                <div class="form-section">
                    <h3><i class="fas fa-briefcase"></i> Détails du stage professionnel</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Offre visée (optionnel)</label>
                        <select name="offre_id" class="form-select">
                            <option value="">Sélectionnez l'offre (facultatif)</option>
                            @foreach($entreprise->offres as $offre)
                                <option value="{{ $offre->id }}">{{ $offre->titre }} ({{ $offre->lieu }}, {{ $offre->duree }} mois)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Objet de la demande</label>
                        <input type="text" name="objet" class="form-input" required
                            value="{{ old('objet') }}"
                            placeholder="ex: Demande de stage académique en informatique">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Disponibilité de début</label>
                            <input type="date" name="date_debut_disponible" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Durée souhaitée (mois)</label>
                            <select name="duree_souhaitee" class="form-select">
                                <option value="">Sélectionnez la durée</option>
                                <option value="1">1 mois</option>
                                <option value="2">2 mois</option>
                                <option value="3">3 mois</option>
                                <option value="4">4 mois</option>
                                <option value="5">5 mois</option>
                                <option value="6">6 mois</option>
                                <option value="12">12 mois</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Disponibilités (description)</label>
                        <input type="text" name="disponibilites" class="form-input" 
                               placeholder="ex: Dès septembre 2025, flexible">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Niveau de rémunération souhaité</label>
                        <select name="remuneration_souhaitee" class="form-select">
                            <option value="">Sélectionnez</option>
                            <option value="non_remunere">Non rémunéré</option>
                            <option value="indemnite_transport">Indemnité de transport uniquement</option>
                            <option value="remunere_faible">Rémunération faible (< 50 000 FCFA)</option>
                            <option value="remunere_moyenne">Rémunération moyenne (50 000 - 100 000 FCFA)</option>
                            <option value="remunere_elevee">Rémunération élevée (> 100 000 FCFA)</option>
                            <option value="negociable">Négociable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Lettre de motivation</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="lettre_motivation" accept=".pdf,.docx" class="file-input" required>
                            <div class="file-input-display">
                                <i class="fas fa-upload"></i>
                                <span>Choisir le fichier (PDF, DOCX)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Compétences techniques</label>
                        <textarea name="competences_techniques" rows="3" class="form-textarea" 
                                  placeholder="Listez vos compétences techniques pertinentes..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Expériences professionnelles</label>
                        <textarea name="experiences_professionnelles" rows="3" class="form-textarea" 
                                  placeholder="Décrivez vos expériences professionnelles antérieures..."></textarea>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-graduation-cap"></i> Diplôme obtenu</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Diplôme obtenu</label>
                                <select name="diplome_obtenu" class="form-select" required>
                                    <option value="">Sélectionnez votre diplôme</option>
                                    <option value="licence" {{ old('diplome_obtenu') == 'licence' ? 'selected' : '' }}>Licence</option>
                                    <option value="master" {{ old('diplome_obtenu') == 'master' ? 'selected' : '' }}>Master</option>
                                    <option value="doctorat" {{ old('diplome_obtenu') == 'doctorat' ? 'selected' : '' }}>Doctorat</option>
                                    <option value="bts" {{ old('diplome_obtenu') == 'bts' ? 'selected' : '' }}>BTS</option>
                                    <option value="dut" {{ old('diplome_obtenu') == 'dut' ? 'selected' : '' }}>DUT</option>
                                    <option value="autre" {{ old('diplome_obtenu') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Établissement</label>
                                <input type="text" name="etablissement_diplome" class="form-input" required 
                                       value="{{ old('etablissement_diplome') }}" 
                                       placeholder="ex: Université d'Abomey-Calavi">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Année d'obtention</label>
                                <input type="text" name="annee_obtention" class="form-input" required 
                                       value="{{ old('annee_obtention') }}" 
                                       placeholder="ex: 2023">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mention</label>
                                <input type="text" name="mention_diplome" class="form-input" 
                                       value="{{ old('mention_diplome') }}" 
                                       placeholder="ex: Très Bien, Bien, Assez Bien">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">CV (PDF)</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="cv" accept=".pdf" class="file-input" required>
                            <div class="file-input-display">
                                <i class="fas fa-upload"></i>
                                <span>Choisir le fichier CV (PDF uniquement)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lettre de recommandation</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="recommandation" accept=".pdf,.docx" class="file-input">
                            <div class="file-input-display">
                                <i class="fas fa-upload"></i>
                                <span>Choisir le fichier (PDF, DOCX)</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Validation et soumission -->
            <div class="mt-6">
                <button type="submit" class="submit-btn primary">
                    <i class="fas fa-paper-plane mr-2"></i> Envoyer la demande de stage
                </button>
            </div>

            @if ($errors->any())
                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <h4 class="font-bold mb-2">Erreurs détectées :</h4>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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

    document.addEventListener('DOMContentLoaded', function () {
        // Gestion dynamique du champ binôme
        const radioSolo = document.querySelector('input[value="solo"]');
        const radioBinome = document.querySelector('input[value="binome"]');
        const binomeFields = document.getElementById('binome-fields');

        if (radioSolo && radioBinome && binomeFields) {
            function toggleBinome() {
                binomeFields.classList.toggle('active', radioBinome.checked);
                const binomeInputs = binomeFields.querySelectorAll('input');
                binomeInputs.forEach(input => {
                    input.required = radioBinome.checked;
                });
            }

            radioSolo.addEventListener('change', toggleBinome);
            radioBinome.addEventListener('change', toggleBinome);
            toggleBinome(); // Initialisation
        }

        // Gestion des fichiers avec preview
        const fileInputs = document.querySelectorAll('.file-input');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const display = this.nextElementSibling;
                const files = this.files;
                if (files.length > 0) {
                    if (files.length === 1) {
                        display.querySelector('span').textContent = files[0].name;
                    } else {
                        display.querySelector('span').textContent = `${files.length} fichiers sélectionnés`;
                    }
                    display.style.background = 'var(--secondary-blue)';
                    display.style.borderColor = 'var(--primary-blue)';
                } else {
                    display.querySelector('span').textContent = display.querySelector('span').getAttribute('data-original') || 'Choisir le fichier';
                    display.style.background = 'var(--background-alt)';
                    display.style.borderColor = 'var(--border-color)';
                }
            });
        });

        // Validation du formulaire
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--danger-color)';
                } else {
                    field.style.borderColor = 'var(--border-color)';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });

        // Gestion des dates
        const dateInputs = document.querySelectorAll('input[type="date"]');
        const today = new Date().toISOString().split('T')[0];
        
        dateInputs.forEach(input => {
            input.min = today; // Minimum aujourd'hui pour les dates de début
        });

        // Synchronisation des dates de début et fin (académique)
        const dateDebut = document.querySelector('input[name="date_debut_souhaitee"]');
        const dateFin = document.querySelector('input[name="date_fin_souhaitee"]');
        const dateDebutPro = document.querySelector('input[name="date_debut_disponible"]');
        
        if (dateDebut && dateFin) {
            dateDebut.addEventListener('change', function() {
                dateFin.min = this.value;
            });
        }

        if (dateDebutPro) {
            // Pas de date de fin synchronisée pour le pro, mais minimum aujourd'hui
        }
    });
</script>
@endpush