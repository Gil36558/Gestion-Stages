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
