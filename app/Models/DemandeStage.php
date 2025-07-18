<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeStage extends Model
{
    protected $table = 'demandes_stages';

    /**
     * Champs autorisés à l'assignation de masse.
     */
    protected $fillable = [
        'type',
        'statut', // <--- Ajouté pour dashboard
        'entreprise_id',
        'periode_debut',
        'periode_fin',
        'periode',
        'objectifs_stage',
        'cv',
        'lettre_motivation',
        'recommandation',
        'piece_identite',
        'portfolio',
        'objet',
        'offre_id',
        'date_debut_disponible',
        'duree_souhaitee',
        'disponibilites',
        'remuneration_souhaitee',
        'competences_techniques',
        'experiences_professionnelles',
        'diplome_obtenu',
        'etablissement_diplome',
        'annee_obtention',
        'mention_diplome',
        // Infos personnelles & formation
        'email',
        'telephone',
        'whatsapp',
        'adresse',
        'etablissement',
        'faculte',
        'filiere',
        'niveau_etudes',
        'annee_academique',
        'matricule',
        // Identité
        'type_piece_identite',
        'numero_piece_identite',
        // Langues & attentes
        'niveau_francais',
        'niveau_anglais',
        'autres_langues',
        'contraintes',
        'attentes',
        // Contact d’urgence
        'contact_urgence_nom',
        'contact_urgence_lien',
        'contact_urgence_telephone',
        'contact_urgence_email',
        // Déclaration / engagement
        'declaration_veracite',
        'engagement_respect',
        'autorisation_donnees',
        'autorisation_evaluation',
        // Stage académique
        'mode',
        'competences_a_developper',
        'nom_binome',
    ];

    /**
     * 🔁 Étudiants liés à la demande (relation N-N via table pivot)
     */
    public function etudiants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'demande_stage_etudiant', 'demande_id', 'etudiant_id')
                    ->withTimestamps();
    }

    /**
     * 📖 Suivis de stage liés (1-N)
     */
    public function suivis(): HasMany
    {
        return $this->hasMany(SuiviStage::class);
    }

    /**
     * 🏢 Entreprise liée à la demande
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * 🔗 Offre visée (optionnelle pour stage pro)
     */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }

    /**
     * 🎯 Stage créé (si demande acceptée)
     */
    public function stage()
    {
        return $this->hasOne(Stage::class, 'demande_stage_id');
    }
}
