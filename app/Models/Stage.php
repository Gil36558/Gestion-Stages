<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entreprise_id',
        'candidature_id',
        'demande_stage_id',
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'statut',
        'objectifs',
        'taches_realisees',
        'competences_acquises',
        'note_etudiant',
        'note_entreprise',
        'commentaire_etudiant',
        'commentaire_entreprise',
        'maitre_stage_nom',
        'maitre_stage_email',
        'maitre_stage_telephone',
        'maitre_stage_poste',
        'rapport_stage',
        'attestation_stage',
        'fiche_evaluation',
        'date_debut_reel',
        'date_fin_reel',
        'date_evaluation',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_debut_reel' => 'datetime',
        'date_fin_reel' => 'datetime',
        'date_evaluation' => 'datetime',
        'note_etudiant' => 'integer',
        'note_entreprise' => 'integer',
    ];

    /**
     * Relation avec l'étudiant
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'entreprise
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Relation avec la candidature (si le stage vient d'une candidature)
     */
    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }

    /**
     * Relation avec la demande de stage (si le stage vient d'une demande directe)
     */
    public function demandeStage(): BelongsTo
    {
        return $this->belongsTo(DemandeStage::class, 'demande_stage_id');
    }

    /**
     * Scopes pour filtrer par statut
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente_debut');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeEvalue($query)
    {
        return $query->where('statut', 'evalue');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    /**
     * Accesseurs pour les statuts en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        return match($this->statut) {
            'en_attente_debut' => 'En attente de début',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'evalue' => 'Évalué',
            'valide' => 'Validé',
            'annule' => 'Annulé',
            default => 'Inconnu'
        };
    }

    /**
     * Accesseur pour la couleur du statut
     */
    public function getStatutCouleurAttribute(): string
    {
        return match($this->statut) {
            'en_attente_debut' => 'warning',
            'en_cours' => 'info',
            'termine' => 'primary',
            'evalue' => 'success',
            'valide' => 'success',
            'annule' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Calcule la durée du stage en jours
     */
    public function getDureeAttribute(): int
    {
        return $this->date_debut->diffInDays($this->date_fin) + 1;
    }

    /**
     * Calcule le pourcentage d'avancement du stage
     */
    public function getPourcentageAvancementAttribute(): int
    {
        if ($this->statut === 'en_attente_debut') {
            return 0;
        }

        if (in_array($this->statut, ['termine', 'evalue', 'valide'])) {
            return 100;
        }

        if ($this->statut === 'en_cours') {
            $dateDebut = $this->date_debut_reel ?? $this->date_debut;
            $dateFin = $this->date_fin;
            $maintenant = Carbon::now();

            if ($maintenant->lt($dateDebut)) {
                return 0;
            }

            if ($maintenant->gt($dateFin)) {
                return 100;
            }

            $dureeTotal = $dateDebut->diffInDays($dateFin);
            $dureeEcoulee = $dateDebut->diffInDays($maintenant);

            return $dureeTotal > 0 ? min(100, round(($dureeEcoulee / $dureeTotal) * 100)) : 0;
        }

        return 0;
    }

    /**
     * Vérifie si le stage peut être démarré
     */
    public function peutEtreDemarre(): bool
    {
        return $this->statut === 'en_attente_debut' && 
               Carbon::now()->gte($this->date_debut);
    }

    /**
     * Vérifie si le stage peut être terminé
     */
    public function peutEtreTermine(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Vérifie si le stage peut être évalué
     */
    public function peutEtreEvalue(): bool
    {
        return $this->statut === 'termine';
    }

    /**
     * Vérifie si le stage est en retard
     */
    public function estEnRetard(): bool
    {
        return $this->statut === 'en_cours' && 
               Carbon::now()->gt($this->date_fin);
    }

    /**
     * Obtient la source du stage (candidature ou demande)
     */
    public function getSourceAttribute(): string
    {
        if ($this->candidature_id) {
            return 'candidature';
        } elseif ($this->demande_stage_id) {
            return 'demande';
        }
        return 'inconnu';
    }

    /**
     * Obtient l'objet source (candidature ou demande)
     */
    public function getObjetSourceAttribute()
    {
        if ($this->candidature_id) {
            return $this->candidature;
        } elseif ($this->demande_stage_id) {
            return $this->demandeStage;
        }
        return null;
    }

    /**
     * Relation avec les entrées du journal de stage
     */
    public function journalEntrees()
    {
        return $this->hasMany(JournalStage::class)->orderBy('date_activite', 'desc');
    }

    /**
     * Obtient les entrées de journal pour une période
     */
    public function journalPourPeriode($dateDebut, $dateFin)
    {
        return $this->journalEntrees()
                   ->whereBetween('date_activite', [$dateDebut, $dateFin])
                   ->get();
    }

    /**
     * Obtient les statistiques du journal
     */
    public function getStatistiquesJournalAttribute()
    {
        $entrees = $this->journalEntrees;
        
        return [
            'total_entrees' => $entrees->count(),
            'entrees_soumises' => $entrees->where('statut', 'soumis')->count(),
            'entrees_validees' => $entrees->where('statut', 'valide')->count(),
            'entrees_rejetees' => $entrees->where('statut', 'rejete')->count(),
            'moyenne_heures' => $entrees->where('heures_travaillees', '>', 0)->avg('heures_travaillees'),
            'moyenne_note' => $entrees->where('note_journee', '>', 0)->avg('note_journee'),
            'derniere_entree' => $entrees->first()?->date_activite,
        ];
    }

    /**
     * Vérifie si le stage peut être annulé
     */
    public function peutEtreAnnule(): bool
    {
        return !in_array($this->statut, ['termine', 'evalue', 'valide']);
    }
}
