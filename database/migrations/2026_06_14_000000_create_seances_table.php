<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seances', function (Blueprint $t) {
            $t->id();
            $t->foreignId('group_id')->constrained()->cascadeOnDelete();
            $t->string('titre');
            $t->date('date_seance');
            $t->time('heure_debut')->nullable();
            $t->time('heure_fin')->nullable();
            $t->string('type')->nullable();
            $t->string('lien_zoom')->nullable();
            $t->text('description')->nullable();
            $t->enum('statut', ['planifiée','réalisée','annulée'])->default('planifiée');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};