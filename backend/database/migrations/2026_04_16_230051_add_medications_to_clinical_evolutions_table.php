<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinical_evolutions', function (Blueprint $table) {
            $table->jsonb('medications')->nullable()->after('vital_signs');
        });
    }

    public function down(): void
    {
        Schema::table('clinical_evolutions', function (Blueprint $table) {
            $table->dropColumn('medications');
        });
    }
};
