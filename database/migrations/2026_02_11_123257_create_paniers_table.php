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
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->string('etudiant_id');
            $table->foreign('etudiant_id')->references('matricule')->on('etudiants');
            $table->timestamps();
        });
        Schema::create('panier_plat', function (Blueprint $table) {
          $table->id();
          $table->foreignId('panier_id')->constrained()->cascadeOnDelete();
          $table->foreignId('plat_id')->constrained()->cascadeOnDelete();
          $table->integer('quantite');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paniers');
    }
};
