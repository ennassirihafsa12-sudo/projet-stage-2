<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marches', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('objet');
            $table->date('date_publication');
            $table->date('date_ouverture_plis');
            $table->unsignedSmallInteger('validite_offre_jours')->default(90);
            $table->string('entreprise')->nullable();
            $table->decimal('montant_estimatif', 15, 2)->nullable();
            $table->string('responsable')->nullable();
            $table->text('description')->nullable();
            $table->string('statut')->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marches');
    }
};
