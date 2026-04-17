<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('system_settings')->insert([
            ['key' => 'institution_name',      'value' => 'SHCSO',                                           'created_at' => now(), 'updated_at' => now()],
            ['key' => 'institution_subtitle',  'value' => 'Sistema de Historias Clínicas y Salud Ocupacional', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'institution_city',      'value' => null,                                               'created_at' => now(), 'updated_at' => now()],
            ['key' => 'footer_note',           'value' => 'Documento confidencial de uso médico.',            'created_at' => now(), 'updated_at' => now()],
            ['key' => 'signature_name',        'value' => 'MÉDICO OCUPACIONAL',                               'created_at' => now(), 'updated_at' => now()],
            ['key' => 'signature_title',       'value' => 'Responsable de Salud Ocupacional',                 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'professional_code',     'value' => null,                                               'created_at' => now(), 'updated_at' => now()],
            ['key' => 'professional_title',    'value' => 'Dr./Dra.',                                         'created_at' => now(), 'updated_at' => now()],
            ['key' => 'logo_path',             'value' => null,                                               'created_at' => now(), 'updated_at' => now()],
            ['key' => 'signature_path',        'value' => null,                                               'created_at' => now(), 'updated_at' => now()],
            ['key' => 'seal_path',             'value' => null,                                               'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
