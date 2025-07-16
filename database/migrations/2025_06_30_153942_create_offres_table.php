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
    Schema::create('offres', function (Blueprint $table) {
        $table->id();
        $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
        $table->string('titre');
        $table->text('description');
        $table->date('date_debut');
        $table->date('date_fin')->nullable();
        $table->string('lieu')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
