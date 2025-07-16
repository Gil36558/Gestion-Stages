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
        Schema::create('demandes_stages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['academique', 'professionnel']);
            $table->string('objet');
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_stages');
    }
};
