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
        Schema::create('medical_certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('certificate_code', 40)->unique();
            $table->uuid('evaluation_id');
            $table->uuid('worker_id');
            $table->date('issue_date');
            $table->enum('medical_aptitude', ['APTO', 'APTO_OBSERVACION', 'APTO_LIMITACIONES', 'NO_APTO']);
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('professional_name', 150);
            $table->string('professional_code', 60);
            $table->text('worker_signature_path')->nullable();
            $table->text('pdf_path')->nullable();
            $table->text('qr_code_data')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('evaluation_id')->references('id')->on('occupational_evaluations')->cascadeOnDelete();
            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('medical_certificates', function (Blueprint $table) {
            $table->index('worker_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_certificates');
    }
};
