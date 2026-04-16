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
        Schema::table('medical_certificates', function (Blueprint $table) {
            $table->date('valid_until')->nullable()->after('issue_date');
        });
    }

    public function down(): void
    {
        Schema::table('medical_certificates', function (Blueprint $table) {
            $table->dropColumn('valid_until');
        });
    }
};
