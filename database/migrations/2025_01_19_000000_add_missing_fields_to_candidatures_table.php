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
        Schema::table('candidatures', function (Blueprint $table) {
            // Corriger la clé étrangère si nécessaire
            $table->dropForeign(['offre_id']);
            $table->foreign('offre_id')->references('id')->on('offres')->onDelete('cascade');
            
            // Ajouter les champs manquants
            $table->text('informations_complementaires')->nullable()->after('message');
            $table->date('date_debut_disponible')->nullable()->after('informations_complementaires');
            $table->integer('duree_souhaitee')->nullable()->after('date_debut_disponible');
            $table->text('competences')->nullable()->after('duree_souhaitee');
            $table->text('experiences')->nullable()->after('competences');
            $table->timestamp('date_reponse')->nullable()->after('motif_refus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn([
                'informations_complementaires',
                'date_debut_disponible',
                'duree_souhaitee',
                'competences',
                'experiences',
                'date_reponse'
            ]);
            
            // Remettre l'ancienne clé étrangère
            $table->dropForeign(['offre_id']);
            $table->foreign('offre_id')->references('id')->on('offres_stages')->onDelete('cascade');
        });
    }
};
