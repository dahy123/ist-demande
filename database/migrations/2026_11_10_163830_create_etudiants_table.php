<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 80);
            $table->string('prenoms', 120)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance', 80)->nullable();
            $table->string('telephone', 40)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('adresse', 160)->nullable();
            $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->nullOnDelete();
            $table->string('mention', 100)->nullable();
            $table->string('parcours', 100)->nullable();
            $table->string('promotion', 20)->nullable();
            $table->string('diplome_vise', 20)->nullable();
            $table->timestamps();

            $table->index(['nom', 'prenoms']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
