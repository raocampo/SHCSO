<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CiiuActivity;
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
        $this->seedCiiuActivities();
        $this->seedCiuoJobPositions();

        Company::query()->updateOrCreate(
            ['ruc' => '0999999999001'],
            [
                'ciiu' => 'Q8620.01',
                'business_name' => 'Empresa Demo SHCSO',
                'work_center' => 'Planta Principal',
                'address' => 'Direccion referencial',
            ]
        );

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

    private function seedCiiuActivities(): void
    {
        $path = database_path('data/ciiu_rev4_inec.csv');
        if (! is_readable($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return;
        }

        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            [$code, $description, $level] = array_pad($row, 3, null);
            $code = trim((string) $code);
            $description = trim((string) $description);
            $level = (int) $level;

            if ($code === '' || $description === '' || $level < 1) {
                continue;
            }

            CiiuActivity::query()->updateOrCreate(
                ['code' => $code],
                [
                    'description' => $description,
                    'level' => $level,
                ]
            );
        }

        fclose($handle);
    }

    private function seedCiuoJobPositions(): void
    {
        $path = database_path('data/ciuo_ecuador.csv');
        if (! is_readable($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return;
        }

        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            [$code, $description, $level] = array_pad($row, 3, null);
            $code = trim((string) $code);
            $description = trim((string) $description);
            $level = (int) $level;

            if ($code === '' || $description === '' || $level < 1) {
                continue;
            }

            $name = mb_strlen($description) > 160
                ? rtrim(mb_substr($description, 0, 157)) . '...'
                : $description;

            JobPosition::query()->updateOrCreate(
                ['ciuo_code' => $code],
                [
                    'ciuo_level' => $level,
                    'ciiu_code' => null,
                    'ciiu_level' => null,
                    'name' => $name,
                    'description' => $description,
                ]
            );
        }

        fclose($handle);
    }
}
