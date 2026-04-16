<?php

namespace Database\Seeders;

use App\Models\DiagnosisCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosisCatalogCie10Seeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('data/catalogo_cie10.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("Archivo no encontrado: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command->error("No se pudo abrir el archivo CSV.");
            return;
        }

        // Leer cabecera y mapear índices
        $header = fgetcsv($handle);
        $header = array_map('trim', $header);
        $idxCode = array_search('CATALOG_KEY', $header);
        $idxName = array_search('NOMBRE', $header);
        $idxValid = array_search('VALID', $header);

        if ($idxCode === false || $idxName === false) {
            $this->command->error("Columnas CATALOG_KEY o NOMBRE no encontradas en el CSV.");
            fclose($handle);
            return;
        }

        $this->command->info("Importando catálogo CIE-10...");

        DB::statement('TRUNCATE TABLE diagnosis_catalog RESTART IDENTITY CASCADE');

        $batch = [];
        $total = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (! isset($row[$idxCode], $row[$idxName])) {
                continue;
            }

            $code = trim($row[$idxCode]);
            $name = trim($row[$idxName]);
            $valid = isset($row[$idxValid]) ? strtoupper(trim($row[$idxValid])) : 'SI';

            // Solo importar códigos vigentes con código y nombre válidos
            if ($code === '' || $name === '' || $valid !== 'SI') {
                $skipped++;
                continue;
            }

            // Normalizar el código: formato CIE-10 con punto (A000 -> A00.0, A0001 no aplica)
            $code = $this->normalizeCode($code);

            $batch[] = ['code' => $code, 'description' => $name];

            if (count($batch) >= 500) {
                DiagnosisCatalog::upsert($batch, ['code'], ['description']);
                $total += count($batch);
                $batch = [];
                $this->command->getOutput()->write('.');
            }
        }

        // Insertar el último lote
        if (! empty($batch)) {
            DiagnosisCatalog::upsert($batch, ['code'], ['description']);
            $total += count($batch);
        }

        fclose($handle);

        $this->command->newLine();
        $this->command->info("✅ CIE-10 importado: {$total} códigos vigentes ({$skipped} omitidos).");
    }

    /**
     * Normaliza el código CIE-10 agregando punto decimal si corresponde.
     * Ejemplos: A000 -> A00.0 | M545 -> M54.5 | Z000 -> Z00.0
     */
    private function normalizeCode(string $code): string
    {
        // Si ya tiene punto, dejarlo como está
        if (str_contains($code, '.')) {
            return $code;
        }

        // Códigos de 4 caracteres: insertar punto antes del último dígito
        if (strlen($code) === 4) {
            return substr($code, 0, 3) . '.' . substr($code, 3);
        }

        // Códigos de 3 o menos caracteres: categoría raíz, dejar sin punto
        return $code;
    }
}
