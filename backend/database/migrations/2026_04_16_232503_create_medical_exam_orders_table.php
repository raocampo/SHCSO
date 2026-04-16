<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_exam_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('worker_id');
            $table->uuid('evaluation_id')->nullable();
            $table->uuid('ordered_by_user_id')->nullable();
            // LAB, IMAGING, PATHOLOGY, FUNCTIONAL
            $table->string('order_type', 30)->default('LAB');
            $table->string('priority', 20)->default('NORMAL'); // URGENT, NORMAL, ROUTINE
            $table->date('order_date');
            $table->string('clinical_indication', 1000)->nullable();
            $table->jsonb('studies'); // [{name, notes}]
            $table->text('additional_notes')->nullable();
            // PENDING, COMPLETED, PARTIAL, CANCELLED
            $table->string('status', 20)->default('PENDING');
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
            $table->foreign('evaluation_id')->references('id')->on('occupational_evaluations')->nullOnDelete();
            $table->foreign('ordered_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['worker_id', 'order_date']);
            $table->index('order_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_exam_orders');
    }
};
