<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entreprise extends Model
{
    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'telephone',
        'secteur',
        'site_web',
        'description',
        'user_id', // il manquait probablement ce champ dans $fillable
    ];

    /**
     * Relation avec les offres
     */
    public function offres()
    {
        return $this->hasMany(Offre::class);
    }

    /**
     * Relation avec les candidatures (via les offres)
     */
    public function candidatures()
    {
        return $this->hasManyThrough(Candidature::class, Offre::class);
    }

    /**
     * Relation avec les demandes de stage directes
     */
    public function demandesStages()
    {
        return $this->hasMany(DemandeStage::class);
    }

    /**
     * Relation avec les stages hébergés par l'entreprise
     */
    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    /**
     * Relation avec l'utilisateur propriétaire de l'entreprise
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Optionnel : alias français si tu préfères aussi pouvoir utiliser $entreprise->utilisateur
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
