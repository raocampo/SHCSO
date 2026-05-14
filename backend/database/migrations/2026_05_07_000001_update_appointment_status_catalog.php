<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('appointments')) {
            return;
        }

        DB::table('appointments')
            ->where('status', 'PROGRAMADA')
            ->update(['status' => 'PENDIENTE']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE appointments ALTER COLUMN status SET DEFAULT 'PENDIENTE'");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('appointments')) {
            return;
        }

        DB::table('appointments')
            ->where('status', 'PENDIENTE')
            ->update(['status' => 'PROGRAMADA']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE appointments ALTER COLUMN status SET DEFAULT 'PROGRAMADA'");
        }
    }
};
