<?php

namespace App\Console\Commands;

use App\Models\DiagnosisCatalog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Cie10ActualizarCommand extends Command
{
    protected $signature = 'cie10:actualizar
                            {--fuente=csv : Fuente de datos: csv o api}
                            {--archivo= : Ruta al CSV (solo con --fuente=csv). Por defecto usa database/data/catalogo_cie10.csv}
                            {--codigo= : Código CIE-10 a buscar/actualizar en la API (solo con --fuente=api)}
                            {--release=2019 : Año de release de la OMS a consultar (por defecto 2019)}';

    protected $description = 'Actualiza el catálogo CIE-10. Fuentes: csv (importación masiva) o api (OMS en tiempo real).';

    // Token en caché para esta ejecución
    private ?string $whoToken = null;

    public function handle(): int
    {
        $fuente = $this->option('fuente');

        if ($fuente === 'api') {
            return $this->actualizarDesdeApi();
        }

        return $this->actualizarDesdeCsv();
    }

    // ─── MODO CSV ─────────────────────────────────────────────────────────────

    private function actualizarDesdeCsv(): int
    {
        $archivo = $this->option('archivo') ?? database_path('data/catalogo_cie10.csv');

        if (! file_exists($archivo)) {
            $this->error("Archivo no encontrado: {$archivo}");
            $this->line("  Coloque el CSV actualizado en <comment>database/data/catalogo_cie10.csv</comment>");
            $this->line("  o indique la ruta con <comment>--archivo=/ruta/al/catalogo.csv</comment>");
            return self::FAILURE;
        }

        $this->info("📂 Importando desde CSV: {$archivo}");

        $handle = fopen($archivo, 'r');
        if ($handle === false) {
            $this->error("No se pudo abrir el archivo.");
            return self::FAILURE;
        }

        $header = array_map('trim', fgetcsv($handle));
        $idxCode  = array_search('CATALOG_KEY', $header);
        $idxName  = array_search('NOMBRE', $header);
        $idxValid = array_search('VALID', $header);

        if ($idxCode === false || $idxName === false) {
            $this->error("El CSV debe tener columnas CATALOG_KEY y NOMBRE.");
            fclose($handle);
            return self::FAILURE;
        }

        DB::statement('TRUNCATE TABLE diagnosis_catalog RESTART IDENTITY CASCADE');

        $batch = [];
        $total = 0;
        $skipped = 0;
        $bar = null;

        while (($row = fgetcsv($handle)) !== false) {
            if (! isset($row[$idxCode], $row[$idxName])) {
                continue;
            }
            $code  = trim($row[$idxCode]);
            $name  = trim($row[$idxName]);
            $valid = isset($row[$idxValid]) ? strtoupper(trim($row[$idxValid])) : 'SI';

            if ($code === '' || $name === '' || $valid !== 'SI') {
                $skipped++;
                continue;
            }

            $batch[] = ['code' => $this->normalizarCodigo($code), 'description' => $name];

            if (count($batch) >= 500) {
                DiagnosisCatalog::upsert($batch, ['code'], ['description']);
                $total += count($batch);
                $batch = [];
                $this->output->write('.');
            }
        }

        if (! empty($batch)) {
            DiagnosisCatalog::upsert($batch, ['code'], ['description']);
            $total += count($batch);
        }

        fclose($handle);

        $this->newLine();
        $this->info("✅ Importación completada: <comment>{$total}</comment> códigos vigentes ({$skipped} omitidos).");
        return self::SUCCESS;
    }

    // ─── MODO API OMS ─────────────────────────────────────────────────────────

    private function actualizarDesdeApi(): int
    {
        $clientId     = config('shcso.who_client_id');
        $clientSecret = config('shcso.who_client_secret');
        $release      = $this->option('release');
        $codigoFiltro = $this->option('codigo');

        if (empty($clientId) || empty($clientSecret)) {
            $this->error("Credenciales WHO no configuradas.");
            $this->line("  Agregue en <comment>.env</comment>:");
            $this->line("    WHO_ICD_CLIENT_ID=su_client_id");
            $this->line("    WHO_ICD_CLIENT_SECRET=su_client_secret");
            $this->line("");
            $this->line("  Regístrese gratis en: <comment>https://icdaccessmanagement.who.int/</comment>");
            return self::FAILURE;
        }

        $this->info("🌐 Conectando con la API de la OMS (ICD-10 release {$release})...");

        // Obtener token OAuth2
        $token = $this->obtenerTokenWho($clientId, $clientSecret);
        if ($token === null) {
            return self::FAILURE;
        }

        $this->info("🔑 Token obtenido correctamente.");

        // Si se especificó un código concreto, solo actualizar ese
        if ($codigoFiltro) {
            return $this->actualizarCodigoIndividual($token, $release, $codigoFiltro);
        }

        // Sincronización completa: recorrer el árbol de la API
        return $this->sincronizarArbolCompleto($token, $release);
    }

    private function obtenerTokenWho(string $clientId, string $clientSecret): ?string
    {
        try {
            $response = Http::asForm()->post('https://icdaccessmanagement.who.int/connect/token', [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'grant_type'    => 'client_credentials',
                'scope'         => 'icdapi_access',
            ]);

            if (! $response->successful()) {
                $this->error("Error obteniendo token: HTTP {$response->status()}");
                $this->line("  Verifique sus credenciales en WHO_ICD_CLIENT_ID y WHO_ICD_CLIENT_SECRET");
                return null;
            }

            return $response->json('access_token');
        } catch (\Exception $e) {
            $this->error("Error de conexión con la OMS: " . $e->getMessage());
            return null;
        }
    }

    private function actualizarCodigoIndividual(string $token, string $release, string $codigo): int
    {
        $this->info("🔍 Buscando código: <comment>{$codigo}</comment>");

        $data = $this->consultarCodigoApi($token, $release, $codigo);

        if ($data === null) {
            $this->warn("Código <comment>{$codigo}</comment> no encontrado en la API de la OMS.");
            return self::FAILURE;
        }

        $descripcion = $data['title']['@value'] ?? null;

        if (! $descripcion) {
            $this->warn("No se pudo obtener la descripción del código {$codigo}.");
            return self::FAILURE;
        }

        DiagnosisCatalog::updateOrCreate(
            ['code' => $codigo],
            ['description' => mb_strtoupper($descripcion)]
        );

        $this->info("✅ Actualizado: <comment>{$codigo}</comment> → {$descripcion}");
        return self::SUCCESS;
    }

    private function sincronizarArbolCompleto(string $token, string $release): int
    {
        $this->info("🌳 Iniciando sincronización completa del árbol ICD-10...");
        $this->warn("  Este proceso puede tardar varios minutos (miles de peticiones).");

        if (! $this->confirm("¿Continuar con la sincronización completa?", true)) {
            $this->info("Cancelado.");
            return self::SUCCESS;
        }

        $rootUrl  = "https://id.who.int/icd/release/10/{$release}";
        $total    = 0;
        $errores  = 0;
        $batch    = [];

        $this->info("Recorriendo árbol desde: {$rootUrl}");
        $this->newLine();

        $this->recorrerNodo($token, $rootUrl, $release, $batch, $total, $errores);

        // Insertar último lote
        if (! empty($batch)) {
            DiagnosisCatalog::upsert($batch, ['code'], ['description']);
            $total += count($batch);
        }

        $this->newLine();
        $this->info("✅ Sincronización completada: <comment>{$total}</comment> códigos actualizados ({$errores} errores).");
        return self::SUCCESS;
    }

    private function recorrerNodo(
        string $token,
        string $url,
        string $release,
        array &$batch,
        int &$total,
        int &$errores,
        int $profundidad = 0
    ): void {
        $data = $this->llamarApiWho($token, $url);

        if ($data === null) {
            $errores++;
            return;
        }

        // Extraer el código del URL
        $code = $this->extraerCodigoDeUrl($url, $release);
        $title = $data['title']['@value'] ?? null;

        if ($code && $title) {
            $batch[] = [
                'code'        => $code,
                'description' => mb_strtoupper($title),
            ];

            if (count($batch) >= 200) {
                DiagnosisCatalog::upsert($batch, ['code'], ['description']);
                $total += count($batch);
                $batch = [];
                $this->output->write('.');
            }
        }

        // Recorrer hijos
        $children = $data['child'] ?? [];
        foreach ($children as $childUrl) {
            usleep(100000); // 100ms entre peticiones para no sobrecargar la API
            $this->recorrerNodo($token, $childUrl, $release, $batch, $total, $errores, $profundidad + 1);
        }
    }

    private function llamarApiWho(string $token, string $url): ?array
    {
        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept'          => 'application/json',
                    'Accept-Language' => 'es',
                    'API-Version'     => 'v2',
                ])
                ->timeout(15)
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // silencioso en árbol
        }

        return null;
    }

    private function consultarCodigoApi(string $token, string $release, string $codigo): ?array
    {
        $codigoLimpio = str_replace('.', '', $codigo);
        $url = "https://id.who.int/icd/release/10/{$release}/{$codigoLimpio}";
        return $this->llamarApiWho($token, $url);
    }

    private function extraerCodigoDeUrl(string $url, string $release): ?string
    {
        $pattern = "/icd\/release\/10\/{$release}\/([A-Z0-9]+(?:\.[0-9]+)?)/";
        if (preg_match($pattern, $url, $matches)) {
            return $this->normalizarCodigo($matches[1]);
        }
        return null;
    }

    // ─── UTILIDADES ───────────────────────────────────────────────────────────

    private function normalizarCodigo(string $code): string
    {
        if (str_contains($code, '.')) {
            return $code;
        }
        if (strlen($code) === 4) {
            return substr($code, 0, 3) . '.' . substr($code, 3);
        }
        return $code;
    }
}
