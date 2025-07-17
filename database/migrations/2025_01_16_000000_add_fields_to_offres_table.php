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
        Schema::table('offres', function (Blueprint $table) {
            // Ajout des nouveaux champs pour les offres
            $table->text('competences_requises')->nullable()->after('description');
            $table->enum('type_stage', ['academique', 'professionnel', 'les_deux'])->default('les_deux')->after('competences_requises');
            $table->string('duree', 100)->nullable()->after('type_stage');
            $table->string('remuneration', 100)->nullable()->after('duree');
            $table->string('niveau_etudes', 100)->nullable()->after('lieu');
            $table->date('date_limite_candidature')->nullable()->after('date_fin');
            $table->integer('nombre_postes')->default(1)->after('date_limite_candidature');
            $table->enum('statut', ['active', 'inactive'])->default('active')->after('nombre_postes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn([
                'competences_requises',
                'type_stage',
                'duree',
                'remuneration',
                'niveau_etudes',
                'date_limite_candidature',
                'nombre_postes',
                'statut'
            ]);
        });
    }
};
