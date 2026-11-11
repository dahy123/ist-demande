<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 60);
            $table->string('detail')->nullable();
            $table->timestamp('date_heure')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_audit');
    }
};
