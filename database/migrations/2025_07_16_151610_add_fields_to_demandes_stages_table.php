<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('demandes_stages', function (Blueprint $table) {
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('offre_id')->nullable()->constrained()->onDelete('set null');

            // Coordonnées
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('adresse')->nullable();

            // Identité
            $table->string('type_piece_identite')->nullable();
            $table->string('numero_piece_identite')->nullable();
            $table->string('piece_identite')->nullable();

            // Formation
            $table->string('etablissement')->nullable();
            $table->string('faculte')->nullable();
            $table->string('filiere')->nullable();
            $table->string('niveau_etudes')->nullable();
            $table->string('annee_academique')->nullable();
            $table->string('matricule')->nullable();

            // Contact urgence
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_lien')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->string('contact_urgence_email')->nullable();

            // Langues
            $table->string('niveau_francais')->nullable();
            $table->string('niveau_anglais')->nullable();
            $table->string('autres_langues')->nullable();

            // Engagements
            $table->boolean('declaration_veracite')->default(false);
            $table->boolean('engagement_respect')->default(false);
            $table->boolean('autorisation_donnees')->default(false);
            $table->boolean('autorisation_evaluation')->default(false);

            // Pièces/fichiers
            $table->string('portfolio')->nullable();
            $table->string('cv')->nullable();
            $table->string('lettre_motivation')->nullable();
            $table->string('recommandation')->nullable();

            // Académique
            $table->enum('mode', ['solo', 'binome'])->nullable();
            $table->string('email_binome')->nullable();
            $table->string('nom_binome')->nullable();
            $table->string('periode')->nullable();
            $table->text('objectifs_stage')->nullable();
            $table->text('competences_a_developper')->nullable();

            // Pro
            $table->date('date_debut_disponible')->nullable();
            $table->integer('duree_souhaitee')->nullable();
            $table->string('disponibilites')->nullable();
            $table->string('remuneration_souhaitee')->nullable();
            $table->text('competences_techniques')->nullable();
            $table->text('experiences_professionnelles')->nullable();

            // Diplôme
            $table->string('diplome_obtenu')->nullable();
            $table->string('etablissement_diplome')->nullable();
            $table->string('annee_obtention')->nullable();
            $table->string('mention_diplome')->nullable();

            // Extras
            $table->text('contraintes')->nullable();
            $table->text('attentes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('demandes_stages', function (Blueprint $table) {
            $table->dropForeign(['entreprise_id']);
            $table->dropForeign(['offre_id']);
            $table->dropColumn([
                'entreprise_id', 'offre_id', 'email', 'telephone', 'whatsapp', 'adresse',
                'type_piece_identite', 'numero_piece_identite', 'piece_identite',
                'etablissement', 'faculte', 'filiere', 'niveau_etudes', 'annee_academique', 'matricule',
                'contact_urgence_nom', 'contact_urgence_lien', 'contact_urgence_telephone', 'contact_urgence_email',
                'niveau_francais', 'niveau_anglais', 'autres_langues',
                'declaration_veracite', 'engagement_respect', 'autorisation_donnees', 'autorisation_evaluation',
                'portfolio', 'cv', 'lettre_motivation', 'recommandation',
                'mode', 'email_binome', 'nom_binome', 'periode', 'objectifs_stage', 'competences_a_developper',
                'date_debut_disponible', 'duree_souhaitee', 'disponibilites', 'remuneration_souhaitee',
                'competences_techniques', 'experiences_professionnelles',
                'diplome_obtenu', 'etablissement_diplome', 'annee_obtention', 'mention_diplome',
                'contraintes', 'attentes'
            ]);
        });
    }
};
