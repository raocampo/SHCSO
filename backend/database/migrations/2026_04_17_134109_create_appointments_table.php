<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('worker_id');
            $table->uuid('doctor_id')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('type', 60)->default('CONSULTA');
            // PENDIENTE | CONFIRMADA | CANCELADA | CANCELADA_PACIENTE | NO_ASISTIO
            $table->string('status', 30)->default('PENDIENTE');
            $table->string('reason', 255)->nullable();
            $table->text('notes')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['appointment_date', 'status']);
            $table->index('worker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
