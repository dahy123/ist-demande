<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attestations', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();
            $table->foreignId('etudiant_id')->constrained('etudiants')->restrictOnDelete();
            $table->string('diplome', 20);
            $table->date('date_deliberation')->nullable();
            $table->string('version', 20)->default('PROVISOIRE');
            $table->date('date_retrait')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attestations');
    }
};
