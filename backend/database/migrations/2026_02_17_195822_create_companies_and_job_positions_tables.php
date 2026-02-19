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
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ruc', 13)->nullable()->unique();
            $table->string('ciiu', 12)->nullable();
            $table->string('business_name', 180);
            $table->string('work_center', 180)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('job_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ciuo_code', 12)->nullable();
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('companies');
    }
};
