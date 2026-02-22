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
       Schema::create('rechargements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('etudiant_id')->constrained('users');  // ou table etudiants
    $table->foreignId('caissier_id')->constrained('users');  // ou table caissiers
    $table->decimal('solde', 10, 2)->default(0);  // nombre de points ajoutés
    $table->string('commentaire')->nullable();  // optionnel
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rechargements');
    }
};
