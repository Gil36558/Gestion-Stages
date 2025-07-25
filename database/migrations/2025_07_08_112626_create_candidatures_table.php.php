<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('offre_id')->constrained('offres_stages')->onDelete('cascade');
            $table->string('cv')->nullable();
            $table->string('lettre')->nullable();
            $table->enum('statut', ['en attente', 'acceptée', 'refusée'])->default('en attente');
            $table->text('motif_refus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
