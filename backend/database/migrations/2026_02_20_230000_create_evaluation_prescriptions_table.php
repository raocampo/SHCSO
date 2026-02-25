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
        Schema::create('evaluation_prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('evaluation_id');
            $table->string('medication', 180);
            $table->string('dosage', 120);
            $table->string('frequency', 120)->nullable();
            $table->string('duration', 120)->nullable();
            $table->text('indications')->nullable();
            $table->timestamps();

            $table->foreign('evaluation_id')
                ->references('id')
                ->on('occupational_evaluations')
                ->cascadeOnDelete();
            $table->index('evaluation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_prescriptions');
    }
};

