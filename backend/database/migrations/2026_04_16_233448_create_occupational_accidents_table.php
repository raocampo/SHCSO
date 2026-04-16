<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occupational_accidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('worker_id');
            $table->uuid('evaluation_id')->nullable();
            $table->uuid('reported_by_user_id')->nullable();
            $table->date('accident_date');
            $table->time('accident_time')->nullable();
            // ACCIDENT, NEAR_MISS, OCCUPATIONAL_DISEASE, COMMUTING
            $table->string('accident_type', 40)->default('ACCIDENT');
            // MINOR, MODERATE, SERIOUS, FATAL
            $table->string('severity', 20)->default('MINOR');
            $table->string('accident_location', 300)->nullable();
            $table->text('description');
            $table->string('body_part_affected', 300)->nullable();
            $table->string('injury_type', 200)->nullable();   // Corte, fractura, quemadura...
            $table->string('immediate_cause', 500)->nullable();
            $table->string('root_cause', 500)->nullable();
            $table->integer('lost_days')->default(0);
            $table->boolean('iess_reported')->default(false);
            $table->string('at01_number', 100)->nullable();
            $table->date('iess_report_date')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->text('preventive_actions')->nullable();
            // OPEN, INVESTIGATING, CLOSED
            $table->string('status', 20)->default('OPEN');
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
            $table->foreign('evaluation_id')->references('id')->on('occupational_evaluations')->nullOnDelete();
            $table->foreign('reported_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['worker_id', 'accident_date']);
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occupational_accidents');
    }
};
