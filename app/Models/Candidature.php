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
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
            default => 'Non spécifié'
        };
    }

    public function getStatutColorAttribute()
    {
        return match($this->statut) {
            'en attente' => 'warning',
            'acceptée' => 'success',
            'refusée' => 'danger',
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

    public function canBeModified()
    {
        return $this->isEnAttente();
    }
}
