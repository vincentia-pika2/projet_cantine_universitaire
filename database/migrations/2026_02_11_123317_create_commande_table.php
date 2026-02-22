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
           Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_commande');
            $table->decimal('montant_total', 10, 2);
            $table->string('statut_commande');
            $table->string('etudiant_id');
            $table->foreign('etudiant_id')->references('matricule')->on('etudiants');
            $table->timestamps();
        });
        Schema::create('commande_plat', function (Blueprint $table) {
         $table->id();
         $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
         $table->foreignId('plat_id')->constrained()->cascadeOnDelete();
         $table->integer('quantite');
        });   
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plats');
    }
};
