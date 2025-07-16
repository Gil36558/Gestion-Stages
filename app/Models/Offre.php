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
        'date_debut',
        'date_fin',
    ];

    // Cast des colonnes en objets Date
    protected $dates = [
        'date_debut',
        'date_fin',
    ];
    
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
    
    // ✅ Relation correcte : une offre appartient à un utilisateur (entreprise)
    public function entreprise()
    {
        return $this->belongsTo(User::class, 'entreprise_id');
    }

    // Relation : une offre peut avoir plusieurs candidatures
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
}
