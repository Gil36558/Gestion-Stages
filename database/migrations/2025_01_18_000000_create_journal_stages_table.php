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
        Schema::create('journal_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Étudiant
            $table->date('date_activite');
            $table->text('taches_effectuees');
            $table->text('observations')->nullable();
            $table->text('difficultes_rencontrees')->nullable();
            $table->text('apprentissages')->nullable();
            $table->integer('heures_travaillees')->nullable();
            $table->json('fichiers_joints')->nullable(); // Photos, documents
            $table->enum('statut', ['brouillon', 'soumis', 'valide', 'rejete'])->default('brouillon');
            
            // Commentaires de l'entreprise
            $table->text('commentaire_entreprise')->nullable();
            $table->timestamp('date_commentaire_entreprise')->nullable();
            $table->foreignId('commentaire_par')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('note_journee')->nullable(); // Note sur 5 pour la journée
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['stage_id', 'date_activite']);
            $table->index(['user_id', 'date_activite']);
            $table->unique(['stage_id', 'date_activite']); // Une seule entrée par jour par stage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_stages');
    }
};
