<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Tracks whether the physical certificate has already been generated/printed
            // (separate from "attestation", which is a different eligibility flag).
            // Defaults to 'no' so a new candidate never starts as already-generated.
            $table->enum('certificate_generated', ['yes', 'no'])->default('no')->after('attestation');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('certificate_generated');
        });
    }
};
