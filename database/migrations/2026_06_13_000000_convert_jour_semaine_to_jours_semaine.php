<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $t) {
            $t->json('jours_semaine')->nullable()->after('notes');
        });

        // Migrate existing single-day values into the new JSON column
        DB::table('groups')->whereNotNull('jour_semaine')->get()->each(function ($g) {
            DB::table('groups')->where('id', $g->id)->update([
                'jours_semaine' => json_encode([$g->jour_semaine]),
            ]);
        });

        Schema::table('groups', function (Blueprint $t) {
            $t->dropColumn('jour_semaine');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $t) {
            $t->enum('jour_semaine', [
                'lundi','mardi','mercredi','jeudi','vendredi','samedi'
            ])->nullable()->after('notes');
        });

        DB::table('groups')->whereNotNull('jours_semaine')->get()->each(function ($g) {
            $days = json_decode($g->jours_semaine, true);
            DB::table('groups')->where('id', $g->id)->update([
                'jour_semaine' => $days[0] ?? null,
            ]);
        });

        Schema::table('groups', function (Blueprint $t) {
            $t->dropColumn('jours_semaine');
        });
    }
};