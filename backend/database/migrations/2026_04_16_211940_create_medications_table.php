<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 30)->unique()->nullable();
            $table->string('generic_name', 200);
            $table->string('commercial_name', 200)->nullable();
            $table->string('concentration', 100)->nullable();
            $table->string('pharmaceutical_form', 100)->nullable();
            $table->string('therapeutic_group', 150)->nullable();
            $table->string('via_administracion', 80)->nullable();
            $table->boolean('controlled')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('generic_name');
            $table->index('therapeutic_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};

