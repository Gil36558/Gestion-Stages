<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offre extends Model
{
    use HasFactory;

    // Champs autorisés à l'assignation en masse
    protected $fillable = [
        'entreprise_id',
        'titre',
        'description',
        'competences_requises',
        'type_stage',
        'duree',
        'remuneration',
        'lieu',
        'date_debut',
        'date_fin',
        'date_limite_candidature',
        'nombre_postes',
        'niveau_etudes',
        'statut',
    ];

    // Cast des colonnes en objets Date
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_limite_candidature' => 'date',
        'nombre_postes' => 'integer',
    ];
    
    // Relation : une offre appartient à une entreprise
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    // Relation : une offre peut avoir plusieurs candidatures
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    // Scopes pour filtrer les offres
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeByType($query, $type)
    {
        if ($type) {
            return $query->where('type_stage', $type);
        }
        return $query;
    }

    public function scopeByLocation($query, $lieu)
    {
        if ($lieu) {
            return $query->where('lieu', 'like', '%' . $lieu . '%');
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('titre', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('competences_requises', 'like', '%' . $search . '%')
                  ->orWhereHas('entreprise', function($eq) use ($search) {
                      $eq->where('nom', 'like', '%' . $search . '%');
                  });
            });
        }
        return $query;
    }

    public function scopeBySecteur($query, $secteur)
    {
        if ($secteur) {
            return $query->whereHas('entreprise', function($q) use ($secteur) {
                $q->where('secteur', $secteur);
            });
        }
        return $query;
    }

    // Accesseurs
    public function getTypeStageDisplayAttribute()
    {
        return match($this->type_stage) {
            'academique' => 'Stage Académique',
            'professionnel' => 'Stage Professionnel',
            'les_deux' => 'Tous types',
            default => 'Non spécifié'
        };
    }

    public function getStatutDisplayAttribute()
    {
        return match($this->statut) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            default => 'Non spécifié'
        };
    }

    // Vérifications
    public function isActive()
    {
        return $this->statut === 'active';
    }

    public function isExpired()
    {
        return $this->date_limite_candidature && $this->date_limite_candidature->isPast();
    }

    public function canReceiveCandidatures()
    {
        return $this->isActive() && !$this->isExpired();
    }
}
