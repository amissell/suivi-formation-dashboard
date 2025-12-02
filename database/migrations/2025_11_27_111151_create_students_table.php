<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cin');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('formation_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->decimal('payment_done', 8, 2)->default(0);
            $table->decimal('payment_remaining', 8, 2)->default(0);
            $table->enum('attestation', ['yes', 'no'])->default('no');
            $table->enum('status', ['aide_vendeur', 'vendeur', 'superviseur', 'CDR']);
            $table->string('city')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
