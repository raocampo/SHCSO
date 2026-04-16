<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_vaccinations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('worker_id');
            $table->uuid('applied_by_user_id')->nullable();
            $table->string('vaccine_name', 200);
            $table->string('commercial_name', 200)->nullable();
            $table->string('lot_number', 100)->nullable();
            $table->string('dose_number', 20)->nullable();   // 1ra, 2da, Refuerzo, Única
            $table->string('route', 50)->nullable();          // IM, SC, ID, Oral
            $table->date('applied_date');
            $table->date('next_dose_date')->nullable();
            $table->string('administered_by', 200)->nullable(); // nombre externo si aplica
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
            $table->foreign('applied_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['worker_id', 'applied_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_vaccinations');
    }
};
