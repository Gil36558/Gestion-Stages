<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfosToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('prenom')->nullable();
            $table->string('matricule')->nullable();
            $table->string('filiere')->nullable();
            $table->string('ecole')->nullable();
            $table->date('date_naissance')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prenom', 'matricule', 'filiere', 'ecole', 'date_naissance']);
        });
    }
}
