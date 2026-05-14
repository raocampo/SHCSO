<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->uuid('worker_id')->nullable()->change();
            $table->string('patient_name', 200)->nullable()->after('worker_id');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('patient_name');
            $table->dropForeign(['worker_id']);
            $table->uuid('worker_id')->nullable(false)->change();
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
        });
    }
};
