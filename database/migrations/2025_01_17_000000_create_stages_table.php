<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Étudiant
            $table->unsignedBigInteger('entreprise_id'); // Entreprise
            
            // Référence vers la candidature ou demande acceptée
            $table->unsignedBigInteger('candidature_id')->nullable();
            $table->unsignedBigInteger('demande_stage_id')->nullable();
            
            // Informations du stage
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('lieu')->nullable();
            
            // Statut du stage
            $table->enum('statut', [
                'en_attente_debut', 
                'en_cours', 
                'termine', 
                'evalue', 
                'valide',
                'annule'
            ])->default('en_attente_debut');
            
            // Informations de suivi
            $table->text('objectifs')->nullable();
            $table->text('taches_realisees')->nullable();
            $table->text('competences_acquises')->nullable();
            $table->decimal('note_etudiant', 4, 2)->nullable(); // Note sur 20
            $table->decimal('note_entreprise', 4, 2)->nullable(); // Note sur 20
            $table->text('commentaire_etudiant')->nullable();
            $table->text('commentaire_entreprise')->nullable();
            
            // Encadrement
            $table->string('maitre_stage_nom')->nullable(); // Nom du maître de stage
            $table->string('maitre_stage_email')->nullable();
            $table->string('maitre_stage_telephone')->nullable();
            $table->string('maitre_stage_poste')->nullable();
            
            // Documents
            $table->string('rapport_stage')->nullable(); // Chemin vers le rapport
            $table->string('attestation_stage')->nullable(); // Chemin vers l'attestation
            $table->string('fiche_evaluation')->nullable(); // Chemin vers la fiche d'évaluation
            
            // Dates importantes
            $table->timestamp('date_debut_reel')->nullable(); // Date réelle de début
            $table->timestamp('date_fin_reel')->nullable(); // Date réelle de fin
            $table->timestamp('date_evaluation')->nullable(); // Date d'évaluation
            
            // Champs d'évaluation détaillée
            $table->text('difficultes_rencontrees')->nullable();
            $table->text('points_positifs')->nullable();
            $table->text('points_ameliorer')->nullable();
            $table->string('satisfaction_generale')->nullable();
            $table->string('objectifs_atteints')->nullable();
            $table->string('recommandation_entreprise')->nullable();
            $table->string('recommandation_stagiaire')->nullable();
            $table->text('points_forts')->nullable();
            $table->text('points_ameliorer_stagiaire')->nullable();
            
            // Évaluations par compétences (pour les entreprises)
            $table->integer('evaluation_competences_techniques')->nullable();
            $table->integer('evaluation_autonomie')->nullable();
            $table->integer('evaluation_communication')->nullable();
            $table->integer('evaluation_integration_equipe')->nullable();
            $table->integer('evaluation_respect_consignes')->nullable();
            $table->integer('evaluation_ponctualite')->nullable();
            
            // Informations d'annulation
            $table->string('motif_annulation')->nullable();
            $table->text('commentaire_annulation')->nullable();
            $table->date('date_annulation')->nullable();
            $table->boolean('notifier_etudiant')->default(false);
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['user_id', 'statut']);
            $table->index(['entreprise_id', 'statut']);
            $table->index('candidature_id');
            $table->index('demande_stage_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
