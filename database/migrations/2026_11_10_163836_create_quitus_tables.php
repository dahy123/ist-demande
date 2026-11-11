<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quitus', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20);
            $table->unsignedSmallInteger('annee');
            $table->foreignId('etudiant_id')->constrained('etudiants')->restrictOnDelete();
            $table->date('date_quitus')->nullable();
            $table->boolean('en_retard')->default(false);
            $table->unsignedInteger('frais_retard')->default(0);
            $table->timestamps();

            $table->unique(['etudiant_id', 'annee']);
        });

        Schema::create('quitus_visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quitus_id')->constrained('quitus')->cascadeOnDelete();
            $table->unsignedTinyInteger('position');
            $table->string('service', 100);
            $table->string('responsable', 100)->nullable();
            $table->string('observation', 160)->nullable();
            $table->date('date_visa')->nullable();
            $table->boolean('valide')->default(false);
            $table->timestamps();

            $table->unique(['quitus_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quitus_visas');
        Schema::dropIfExists('quitus');
    }
};
