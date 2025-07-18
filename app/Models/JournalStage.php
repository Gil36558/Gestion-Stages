<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class JournalStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'user_id',
        'date_activite',
        'taches_effectuees',
        'observations',
        'difficultes_rencontrees',
        'apprentissages',
        'heures_travaillees',
        'fichiers_joints',
        'statut',
        'commentaire_entreprise',
        'date_commentaire_entreprise',
        'commentaire_par',
        'note_journee',
    ];

    protected $casts = [
        'date_activite' => 'date',
        'fichiers_joints' => 'array',
        'date_commentaire_entreprise' => 'datetime',
        'heures_travaillees' => 'integer',
        'note_journee' => 'integer',
    ];

    /**
     * Relation avec le stage
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Relation avec l'étudiant
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec la personne qui a commenté (entreprise)
     */
    public function commentateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commentaire_par');
    }

    /**
     * Scopes pour filtrer par statut
     */
    public function scopeBrouillon($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeSoumis($query)
    {
        return $query->where('statut', 'soumis');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }

    /**
     * Scope pour une période donnée
     */
    public function scopePourPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_activite', [$dateDebut, $dateFin]);
    }

    /**
     * Scope pour une semaine donnée
     */
    public function scopePourSemaine($query, $date)
    {
        $debut = Carbon::parse($date)->startOfWeek();
        $fin = Carbon::parse($date)->endOfWeek();
        
        return $query->whereBetween('date_activite', [$debut, $fin]);
    }

    /**
     * Accesseur pour le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'Brouillon',
            'soumis' => 'Soumis',
            'valide' => 'Validé',
            'rejete' => 'Rejeté',
            default => 'Inconnu'
        };
    }

    /**
     * Accesseur pour la couleur du statut
     */
    public function getStatutCouleurAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'gray',
            'soumis' => 'blue',
            'valide' => 'green',
            'rejete' => 'red',
            default => 'gray'
        };
    }

    /**
     * Vérifie si l'entrée peut être modifiée
     */
    public function peutEtreModifiee(): bool
    {
        return in_array($this->statut, ['brouillon', 'rejete']);
    }

    /**
     * Vérifie si l'entrée peut être soumise
     */
    public function peutEtreSoumise(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Vérifie si l'entrée peut être commentée par l'entreprise
     */
    public function peutEtreCommentee(): bool
    {
        return $this->statut === 'soumis';
    }

    /**
     * Vérifie si l'entrée a été commentée
     */
    public function estCommentee(): bool
    {
        return !empty($this->commentaire_entreprise);
    }

    /**
     * Obtient le nombre de fichiers joints
     */
    public function getNombreFichiersAttribute(): int
    {
        return is_array($this->fichiers_joints) ? count($this->fichiers_joints) : 0;
    }

    /**
     * Obtient la note sous forme d'étoiles
     */
    public function getNoteEtoilesAttribute(): string
    {
        if (!$this->note_journee) {
            return '';
        }
        
        return str_repeat('⭐', $this->note_journee) . str_repeat('☆', 5 - $this->note_journee);
    }

    /**
     * Vérifie si c'est une entrée récente (moins de 24h)
     */
    public function estRecente(): bool
    {
        return $this->created_at->diffInHours(now()) < 24;
    }

    /**
     * Obtient le jour de la semaine en français
     */
    public function getJourSemaineAttribute(): string
    {
        return $this->date_activite->locale('fr')->dayName;
    }

    /**
     * Calcule le temps écoulé depuis la soumission
     */
    public function getTempsDepuisSoumissionAttribute(): string
    {
        if ($this->statut !== 'soumis') {
            return '';
        }
        
        return $this->updated_at->diffForHumans();
    }
}
