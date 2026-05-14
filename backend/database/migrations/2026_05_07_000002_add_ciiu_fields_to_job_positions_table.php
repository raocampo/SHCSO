<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_positions', function (Blueprint $table) {
            if (! Schema::hasColumn('job_positions', 'ciiu_code')) {
                $table->string('ciiu_code', 12)->nullable()->after('ciuo_code')->index();
            }
            if (! Schema::hasColumn('job_positions', 'ciiu_level')) {
                $table->unsignedTinyInteger('ciiu_level')->nullable()->after('ciiu_code')->index();
            }
        });

    }

    public function down(): void
    {
        Schema::table('job_positions', function (Blueprint $table) {
            if (Schema::hasColumn('job_positions', 'ciiu_level')) {
                $table->dropColumn('ciiu_level');
            }
            if (Schema::hasColumn('job_positions', 'ciiu_code')) {
                $table->dropColumn('ciiu_code');
            }
        });
    }
};
