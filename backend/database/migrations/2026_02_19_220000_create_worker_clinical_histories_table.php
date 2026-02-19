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
        Schema::create('worker_clinical_histories', function (Blueprint $table) {
            $table->uuid('worker_id')->primary();
            $table->text('personal_background')->nullable();
            $table->text('family_background')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medication')->nullable();
            $table->text('pathological_history')->nullable();
            $table->text('surgical_history')->nullable();
            $table->text('occupational_history')->nullable();
            $table->text('lifestyle_notes')->nullable();
            $table->text('longitudinal_notes')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_clinical_histories');
    }
};

