<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_evolutions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('worker_id');
            $table->uuid('evaluation_id')->nullable();
            $table->uuid('author_user_id')->nullable();
            $table->string('evolution_type', 30)->default('SEGUIMIENTO');
            // SOAP
            $table->text('subjective')->nullable();
            $table->text('objective')->nullable();
            $table->text('assessment')->nullable();
            $table->text('plan')->nullable();
            // Signos vitales (JSON: bp, temp, hr, rr, weight, height)
            $table->jsonb('vital_signs')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
            $table->foreign('evaluation_id')->references('id')->on('occupational_evaluations')->nullOnDelete();
            $table->foreign('author_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index('worker_id');
            $table->index(['worker_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_evolutions');
    }
};

