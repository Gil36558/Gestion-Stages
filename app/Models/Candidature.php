<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'offre_id',
        'cv',
        'lettre',
        'message',
        'statut',
        'motif_refus',
        'informations_complementaires',
        'date_debut_disponible',
        'duree_souhaitee',
        'competences',
        'experiences',
        'date_reponse',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_debut_disponible' => 'date',
        'date_reponse' => 'datetime',
        'duree_souhaitee' => 'integer',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function stage()
    {
        return $this->hasOne(Stage::class);
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en attente');
    }

    public function scopeAcceptees($query)
    {
        return $query->where('statut', 'acceptée');
    }

    public function scopeRefusees($query)
    {
        return $query->where('statut', 'refusée');
    }

    // Accesseurs
    public function getStatutDisplayAttribute()
    {
        return match($this->statut) {
            'en attente' => 'En attente',
            'acceptée' => 'Acceptée',
            'refusée' => 'Refusée',
            'annulée' => 'Annulée',
            default => 'Non spécifié'
        };
    }

    public function getStatutFrancaisAttribute()
    {
        return $this->getStatutDisplayAttribute();
    }

    public function getStatutColorAttribute()
    {
        return match($this->statut) {
            'en attente' => 'warning',
            'acceptée' => 'success',
            'refusée' => 'danger',
            'annulée' => 'secondary',
            default => 'secondary'
        };
    }

    // Méthodes utilitaires
    public function isEnAttente()
    {
        return $this->statut === 'en attente';
    }

    public function isAcceptee()
    {
        return $this->statut === 'acceptée';
    }

    public function isRefusee()
    {
        return $this->statut === 'refusée';
    }

    public function isAnnulee()
    {
        return $this->statut === 'annulée';
    }

    public function canBeModified()
    {
        return $this->isEnAttente();
    }

    public function canBeCancelled()
    {
        return $this->isEnAttente();
    }

    // Méthodes pour les fichiers
    public function hasCv()
    {
        return !empty($this->cv);
    }

    public function hasLettre()
    {
        return !empty($this->lettre);
    }

    public function getCvUrlAttribute()
    {
        return $this->cv ? asset('storage/' . $this->cv) : null;
    }

    public function getLettreUrlAttribute()
    {
        return $this->lettre ? asset('storage/' . $this->lettre) : null;
    }

    public function etudiant()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

}

