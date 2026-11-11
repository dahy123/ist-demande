<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecoles', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('nom', 120);
            $table->timestamps();
        });

        Schema::create('parcours', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('libelle', 120)->nullable();
            $table->foreignId('ecole_id')->constrained('ecoles')->restrictOnDelete();
            $table->string('mention', 40)->nullable();
            $table->string('niveaux', 30)->default('L1,L2,L3,M1,M2');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcours');
        Schema::dropIfExists('ecoles');
    }
};
