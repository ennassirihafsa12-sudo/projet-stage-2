<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marche_id')->constrained('marches')->cascadeOnDelete();
            $table->string('nom');
            $table->unsignedTinyInteger('ordre');
            $table->date('date_prevue')->nullable();
            $table->date('date_reelle')->nullable();
            $table->string('statut')->default('pas_commencee');
            $table->timestamps();

            $table->unique(['marche_id', 'nom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapes');
    }
};
