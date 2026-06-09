<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ciiu_activities')) {
            Schema::create('ciiu_activities', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 12)->unique();
                $table->text('description');
                $table->unsignedTinyInteger('level')->index();
                $table->timestamps();
            });
        }

        Schema::table('job_positions', function (Blueprint $table) {
            if (! Schema::hasColumn('job_positions', 'ciuo_level')) {
                $table->unsignedTinyInteger('ciuo_level')->nullable()->after('ciuo_code')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_positions', function (Blueprint $table) {
            if (Schema::hasColumn('job_positions', 'ciuo_level')) {
                $table->dropColumn('ciuo_level');
            }
        });

        Schema::dropIfExists('ciiu_activities');
    }
};
