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
            // Vérifier et ajouter les champs manquants
            if (!Schema::hasColumn('candidatures', 'message')) {
                $table->text('message')->nullable();
            }
            if (!Schema::hasColumn('candidatures', 'informations_complementaires')) {
                $table->text('informations_complementaires')->nullable();
            }
            if (!Schema::hasColumn('candidatures', 'date_debut_disponible')) {
                $table->date('date_debut_disponible')->nullable();
            }
            if (!Schema::hasColumn('candidatures', 'duree_souhaitee')) {
                $table->integer('duree_souhaitee')->nullable();
            }
            if (!Schema::hasColumn('candidatures', 'competences')) {
                $table->text('competences')->nullable();
            }
            if (!Schema::hasColumn('candidatures', 'experiences')) {
                $table->text('experiences')->nullable();
            }
            if (!Schema::hasColumn('candidatures', 'date_reponse')) {
                $table->timestamp('date_reponse')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn([
                'message',
                'informations_complementaires', 
                'date_debut_disponible',
                'duree_souhaitee',
                'competences',
                'experiences',
                'date_reponse'
            ]);
        });
    }
};
