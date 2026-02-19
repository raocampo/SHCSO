<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\DiagnosisCatalog;
use App\Models\JobPosition;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::query()->firstOrCreate(
            ['ruc' => '0999999999001'],
            [
                'ciiu' => 'Q8621.01',
                'business_name' => 'Empresa Demo SHCSO',
                'work_center' => 'Planta Principal',
                'address' => 'Direccion referencial',
            ]
        );

        $positions = [
            ['ciuo_code' => '2261', 'name' => 'Medico Ocupacional', 'description' => 'Responsable de evaluaciones y certificados ocupacionales'],
            ['ciuo_code' => '3256', 'name' => 'Enfermeria', 'description' => 'Apoyo en toma de signos y registro clinico'],
            ['ciuo_code' => '4321', 'name' => 'Recepcion', 'description' => 'Agendamiento y registro de pacientes'],
        ];

        foreach ($positions as $position) {
            JobPosition::query()->firstOrCreate(
                ['name' => $position['name']],
                $position
            );
        }

        $diagnoses = [
            'Z00.0' => 'Examen medico general',
            'M54.5' => 'Lumbalgia',
            'H52.4' => 'Presbicia',
        ];

        foreach ($diagnoses as $code => $description) {
            DiagnosisCatalog::query()->firstOrCreate(
                ['code' => $code],
                ['description' => $description]
            );
        }
    }
}
