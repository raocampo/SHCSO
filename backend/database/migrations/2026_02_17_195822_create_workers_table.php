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
        Schema::create('workers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('history_number', 30)->unique();
            $table->string('file_number', 30)->unique();
            $table->string('document_type', 30);
            $table->string('document_number', 30)->unique();
            $table->string('first_name', 120);
            $table->string('last_name', 120);
            $table->string('email', 160)->nullable();
            $table->string('phone', 30)->nullable();
            $table->date('birth_date');
            $table->enum('sex', ['M', 'F', 'O']);
            $table->string('blood_type', 10)->nullable();
            $table->string('laterality', 15)->nullable();
            $table->boolean('is_pregnant')->nullable();
            $table->boolean('has_disability')->nullable();
            $table->boolean('catastrophic_disease')->nullable();
            $table->boolean('is_elderly')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained('job_positions')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('workers', function (Blueprint $table) {
            $table->index('document_number');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
