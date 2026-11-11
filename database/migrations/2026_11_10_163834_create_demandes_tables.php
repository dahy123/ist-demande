<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();
            $table->foreignId('type_demande_id')->constrained('types_demande')->restrictOnDelete();
            $table->foreignId('etudiant_id')->constrained('etudiants')->restrictOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('niveau', 10)->nullable();
            $table->string('parcours', 20)->nullable();
            $table->string('annee_universitaire', 9)->nullable();
            $table->string('statut', 30)->default('ENREGISTREE')->index();
            $table->dateTime('date_depot');
            $table->dateTime('date_livraison')->nullable();
            $table->string('mode_retrait', 20)->nullable();
            $table->text('observation')->nullable();
            $table->foreignId('cree_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['etudiant_id', 'type_demande_id']);
        });

        Schema::create('pieces_fournies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->cascadeOnDelete();
            $table->foreignId('piece_requise_id')->constrained('pieces_requises')->cascadeOnDelete();
            $table->boolean('fournie')->default(false);
            $table->date('date_fourniture')->nullable();
            $table->timestamps();

            $table->unique(['demande_id', 'piece_requise_id']);
        });

        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->cascadeOnDelete();
            $table->string('type_justificatif', 30);
            $table->string('numero', 30);
            $table->unsignedSmallInteger('annee');
            $table->date('date_paiement')->nullable();
            $table->unsignedInteger('montant_paye');
            $table->unsignedInteger('montant_attendu')->nullable();
            $table->foreignId('saisi_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['type_justificatif', 'numero', 'annee']);
        });

        Schema::create('historique_statut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->cascadeOnDelete();
            $table->string('ancien_statut', 30)->nullable();
            $table->string('nouveau_statut', 30);
            $table->foreignId('utilisateur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('commentaire', 200)->nullable();
            $table->timestamp('date_heure')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_statut');
        Schema::dropIfExists('paiements');
        Schema::dropIfExists('pieces_fournies');
        Schema::dropIfExists('demandes');
    }
};
