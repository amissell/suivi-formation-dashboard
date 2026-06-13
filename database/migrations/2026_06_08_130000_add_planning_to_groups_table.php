<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $t) {
            // Weekly recurring schedule slot
            $t->enum('jour_semaine', [
                'lundi','mardi','mercredi','jeudi','vendredi','samedi'
            ])->nullable()->after('notes');

            $t->time('slot_heure_debut')->nullable()->after('jour_semaine');
            $t->time('slot_heure_fin')->nullable()->after('slot_heure_debut');

            // When the group's formation officially ends (for "slot libéré" logic)
            $t->date('date_fin')->nullable()->after('slot_heure_fin');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $t) {
            $t->dropColumn(['jour_semaine', 'slot_heure_debut', 'slot_heure_fin', 'date_fin']);
        });
    }
};