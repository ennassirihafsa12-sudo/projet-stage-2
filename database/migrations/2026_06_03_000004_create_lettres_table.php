<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lettres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marche_id')->constrained('marches')->cascadeOnDelete();
            $table->string('type');
            $table->string('entreprise');
            $table->string('format')->default('pdf');
            $table->string('fichier_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lettres');
    }
};
