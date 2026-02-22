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
    {Schema::create('etudiants', function (Blueprint $table) {
    $table->id();
    $table->string('matricule')->unique();
    $table->string('nom');
    $table->string('prenom');
    $table->string('email')->unique();
    $table->string('mot_de_passe');
    $table->string('filiere');
    $table->decimal('solde', 10, 2)->default(0);
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
