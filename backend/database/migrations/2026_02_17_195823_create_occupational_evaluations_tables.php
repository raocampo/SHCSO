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
        Schema::create('occupational_evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('worker_id');
            $table->uuid('evaluator_user_id');
            $table->enum('evaluation_type', ['INGRESO', 'PERIODICO', 'REINTEGRO', 'RETIRO']);
            $table->date('attention_date');
            $table->text('consultation_reason');
            $table->json('personal_background')->nullable();
            $table->text('current_problem')->nullable();
            $table->json('vital_signs')->nullable();
            $table->json('physical_exam')->nullable();
            $table->json('risk_factors')->nullable();
            $table->json('labor_activity_history')->nullable();
            $table->json('extra_activities')->nullable();
            $table->json('exam_results')->nullable();
            $table->enum('medical_aptitude', ['APTO', 'APTO_OBSERVACION', 'APTO_LIMITACIONES', 'NO_APTO']);
            $table->text('restrictions')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('retirement_notes')->nullable();
            $table->string('professional_name', 150);
            $table->string('professional_code', 60);
            $table->text('worker_signature_path')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
            $table->foreign('evaluator_user_id')->references('id')->on('users')->restrictOnDelete();
            $table->index('attention_date');
        });

        Schema::create('evaluation_diagnoses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('evaluation_id');
            $table->string('diagnosis_code', 12);
            $table->enum('diagnosis_type', ['PRE', 'DEF']);
            $table->text('notes')->nullable();

            $table->foreign('evaluation_id')->references('id')->on('occupational_evaluations')->cascadeOnDelete();
            $table->foreign('diagnosis_code')->references('code')->on('diagnosis_catalog')->restrictOnDelete();
        });

        Schema::create('evaluation_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('evaluation_id');
            $table->string('file_name', 255);
            $table->text('file_path');
            $table->string('mime_type', 100);
            $table->uuid('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('evaluation_id')->references('id')->on('occupational_evaluations')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_attachments');
        Schema::dropIfExists('evaluation_diagnoses');
        Schema::dropIfExists('occupational_evaluations');
    }
};
