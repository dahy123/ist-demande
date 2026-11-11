<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_demande', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('libelle', 80);
            $table->unsignedSmallInteger('delai_cible_jours')->nullable();
            $table->timestamps();
        });

        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_demande_id')->constrained('types_demande')->cascadeOnDelete();
            $table->string('niveau_diplome', 20)->nullable();
            $table->unsignedInteger('montant');
            $table->unsignedInteger('montant_retard')->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->timestamps();
        });

        Schema::create('pieces_requises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_demande_id')->constrained('types_demande')->cascadeOnDelete();
            $table->string('libelle', 160);
            $table->boolean('obligatoire')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pieces_requises');
        Schema::dropIfExists('tarifs');
        Schema::dropIfExists('types_demande');
    }
};
