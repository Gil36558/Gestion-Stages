<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Champs modifiables en masse
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'prenom',
        'matricule',
        'filiere',
        'ecole',
        'date_naissance',
        'telephone',
        'adresse',
    ];

    /**
     * Champs masqués dans les tableaux/JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversions automatiques de types
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ uniquement
        'date_naissance' => 'date',
    ];

    /**
     * 🔁 Une entreprise (relation 1-1)
     */
    public function entreprise(): HasOne
    {
        return $this->hasOne(Entreprise::class, 'user_id');
    }

    /**
     * 📄 Documents de l'étudiant (1-n)
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * 📬 Candidatures envoyées (1-n)
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * 📋 Offres publiées (pour les entreprises) (1-n via entreprise)
     */
    public function offres()
    {
        if ($this->role === 'entreprise' && $this->entreprise) {
            return $this->entreprise->offres();
        }
        return collect(); // Retourne une collection vide pour les non-entreprises
    }

    /**
     * 📌 Demandes de stage associées à cet étudiant (n-n via pivot)
     */
    public function demandesStages(): BelongsToMany
    {
        return $this->belongsToMany(DemandeStage::class, 'demande_stage_etudiant', 'etudiant_id', 'demande_id')
                    ->withTimestamps()
                    ->withPivot('id'); // utile pour le suivi
    }

    /**
     * 🎯 Stages de l'étudiant (1-n)
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    /**
     * 🔒 Vérifie si l'utilisateur est une entreprise
     */
    public function estEntreprise(): bool
    {
        return $this->role === 'entreprise';
    }

    /**
     * 🎓 Vérifie si l'utilisateur est un étudiant
     */
    public function estEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    /**
     * 👑 Vérifie si l'utilisateur est un administrateur
     */
    public function estAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 🔎 Scope utilisateurs entreprise
     */
    public function scopeEntreprises($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('role', 'entreprise');
    }

    /**
     * 🔎 Scope utilisateurs étudiant
     */
    public function scopeEtudiants($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('role', 'etudiant');
    }
}
