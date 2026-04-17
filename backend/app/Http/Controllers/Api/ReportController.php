<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\OccupationalEvaluation;
use App\Models\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function applyDateRange($query, ?string $dateFrom, ?string $dateTo)
    {
        if ($dateFrom) {
            $query->whereDate('attention_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('attention_date', '<=', $dateTo);
        }

        return $query;
    }

    public function dashboard(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        $workersCount = Worker::query()->count();
        $evaluationsQuery = OccupationalEvaluation::query();
        $certificatesQuery = MedicalCertificate::query();

        $this->applyDateRange($evaluationsQuery, $dateFrom, $dateTo);
        if ($dateFrom) {
            $certificatesQuery->whereDate('issue_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $certificatesQuery->whereDate('issue_date', '<=', $dateTo);
        }

        $evaluationsCount = (clone $evaluationsQuery)->count();
        $certificatesCount = (clone $certificatesQuery)->count();
        $pendingCertificates = (clone $evaluationsQuery)
            ->whereDoesntHave('certificates')
            ->count();

        $aptitudeDistribution = (clone $evaluationsQuery)
            ->select('medical_aptitude', DB::raw('COUNT(*) as total'))
            ->groupBy('medical_aptitude')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => [
                'totals' => [
                    'workers' => $workersCount,
                    'evaluations' => $evaluationsCount,
                    'certificates' => $certificatesCount,
                    'pending_certificates' => $pendingCertificates,
                ],
                'aptitude_distribution' => $aptitudeDistribution,
            ],
        ]);
    }

    public function aptitudeByCompany(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $limit = (int) ($validated['limit'] ?? 50);
        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;
        $companyIdExpression = 'COALESCE(companies.id, 0)';
        $companyNameExpression = "COALESCE(companies.business_name, 'SIN EMPRESA')";

        $query = OccupationalEvaluation::query()
            ->join('workers', 'workers.id', '=', 'occupational_evaluations.worker_id')
            ->leftJoin('companies', 'companies.id', '=', 'workers.company_id')
            ->selectRaw("
                {$companyIdExpression} as company_id,
                {$companyNameExpression} as company_name,
                occupational_evaluations.medical_aptitude,
                COUNT(*) as total
            ");

        $this->applyDateRange($query, $dateFrom, $dateTo);

        $rows = $query
            ->groupByRaw("{$companyIdExpression}, {$companyNameExpression}, occupational_evaluations.medical_aptitude")
            ->orderByRaw("{$companyNameExpression} asc")
            ->limit($limit * 4)
            ->get();

        $grouped = $rows
            ->groupBy(fn ($row) => (string) $row->company_id)
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'company_id' => $first->company_id,
                    'company_name' => $first->company_name,
                    'totals_by_aptitude' => $items->mapWithKeys(
                        fn ($item) => [$item->medical_aptitude => (int) $item->total]
                    ),
                    'total_evaluations' => (int) $items->sum('total'),
                ];
            })
            ->sortByDesc('total_evaluations')
            ->take($limit)
            ->values();

        return response()->json([
            'ok' => true,
            'data' => $grouped,
        ]);
    }

    public function topDiagnoses(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $limit = (int) ($validated['limit'] ?? 10);
        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        $query = DB::table('evaluation_diagnoses as ed')
            ->join('diagnosis_catalog as dc', 'dc.code', '=', 'ed.diagnosis_code')
            ->join('occupational_evaluations as oe', 'oe.id', '=', 'ed.evaluation_id')
            ->selectRaw('ed.diagnosis_code as code, dc.description, COUNT(*) as total');

        if ($dateFrom) {
            $query->whereDate('oe.attention_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('oe.attention_date', '<=', $dateTo);
        }

        $result = $query
            ->groupBy('ed.diagnosis_code', 'dc.description')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $result,
        ]);
    }

    public function monthlyActivity(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'months' => ['nullable', 'integer', 'min:1', 'max:36'],
        ]);

        $months = (int) ($validated['months'] ?? 12);
        $startDate = now()->startOfMonth()->subMonths($months - 1)->toDateString();
        $driver = DB::connection()->getDriverName();

        $monthExpression = match ($driver) {
            'sqlite' => "strftime('%Y-%m', attention_date)",
            'mysql' => "DATE_FORMAT(attention_date, '%Y-%m')",
            default => "TO_CHAR(attention_date, 'YYYY-MM')",
        };

        $rows = OccupationalEvaluation::query()
            ->selectRaw("{$monthExpression} as month_key, evaluation_type, COUNT(*) as total")
            ->whereDate('attention_date', '>=', $startDate)
            ->groupBy('month_key', 'evaluation_type')
            ->orderBy('month_key')
            ->get();

        $grouped = $rows
            ->groupBy('month_key')
            ->map(function ($items, $month) {
                return [
                    'month' => $month,
                    'total' => (int) $items->sum('total'),
                    'by_evaluation_type' => $items->mapWithKeys(
                        fn ($item) => [$item->evaluation_type => (int) $item->total]
                    ),
                ];
            })
            ->values();

        return response()->json([
            'ok' => true,
            'data' => $grouped,
        ]);
    }

    public function exportExcel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $validated = $request->validate([
            'company_id' => ['nullable', 'uuid'],
            'date_from'  => ['nullable', 'date'],
            'date_to'    => ['nullable', 'date'],
            'type'       => ['nullable', 'in:workers,evaluations,certificates,accidents'],
        ]);

        $type     = $validated['type'] ?? 'evaluations';
        $dateFrom = $validated['date_from'] ?? null;
        $dateTo   = $validated['date_to'] ?? null;

        $rows    = [];
        $headers = [];
        $title   = '';

        if ($type === 'workers') {
            $title   = 'Trabajadores';
            $headers = ['Historia', 'Nombre', 'Documento', 'Empresa', 'Cargo', 'Sexo', 'Fecha Nacimiento', 'Activo'];
            $query   = Worker::with(['company:id,business_name', 'jobPosition:id,name'])
                ->when($validated['company_id'] ?? null, fn ($q, $id) => $q->where('company_id', $id))
                ->orderBy('last_name')
                ->cursor();
            foreach ($query as $w) {
                $rows[] = [
                    $w->history_number,
                    trim($w->first_name . ' ' . $w->last_name),
                    $w->document_number,
                    $w->company?->business_name ?? '-',
                    $w->jobPosition?->name ?? '-',
                    $w->sex,
                    $w->birth_date?->toDateString() ?? '-',
                    $w->is_active ? 'Sí' : 'No',
                ];
            }
        } elseif ($type === 'certificates') {
            $title   = 'Certificados';
            $headers = ['Código', 'Trabajador', 'Empresa', 'Aptitud', 'Fecha Emisión', 'Válido Hasta'];
            $query   = MedicalCertificate::with(['worker:id,first_name,last_name,document_number', 'worker.company:id,business_name'])
                ->when($dateFrom, fn ($q) => $q->whereDate('issue_date', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('issue_date', '<=', $dateTo))
                ->orderByDesc('issue_date')
                ->cursor();
            foreach ($query as $c) {
                $rows[] = [
                    $c->certificate_code,
                    trim(($c->worker->first_name ?? '') . ' ' . ($c->worker->last_name ?? '')),
                    $c->worker?->company?->business_name ?? '-',
                    $c->medical_aptitude,
                    $c->issue_date?->toDateString() ?? '-',
                    $c->valid_until?->toDateString() ?? '-',
                ];
            }
        } elseif ($type === 'accidents') {
            $title   = 'Accidentes Laborales';
            $headers = ['Trabajador', 'Empresa', 'Fecha', 'Tipo', 'Severidad', 'Estado', 'Días Pérdida', 'IESS', 'AT-01'];
            $query   = \App\Models\OccupationalAccident::with(['worker:id,first_name,last_name', 'worker.company:id,business_name'])
                ->when($dateFrom, fn ($q) => $q->whereDate('accident_date', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('accident_date', '<=', $dateTo))
                ->orderByDesc('accident_date')
                ->cursor();
            $typeMap = ['ACCIDENT' => 'Accidente', 'NEAR_MISS' => 'Casi-accidente', 'OCCUPATIONAL_DISEASE' => 'Enf. Ocupacional', 'COMMUTING' => 'In itinere'];
            $sevMap  = ['MINOR' => 'Leve', 'MODERATE' => 'Moderado', 'SERIOUS' => 'Grave', 'FATAL' => 'Fatal'];
            $staMap  = ['OPEN' => 'Abierto', 'INVESTIGATING' => 'Investigando', 'CLOSED' => 'Cerrado'];
            foreach ($query as $a) {
                $rows[] = [
                    trim(($a->worker->first_name ?? '') . ' ' . ($a->worker->last_name ?? '')),
                    $a->worker?->company?->business_name ?? '-',
                    $a->accident_date?->toDateString() ?? '-',
                    $typeMap[$a->accident_type] ?? $a->accident_type,
                    $sevMap[$a->severity] ?? $a->severity,
                    $staMap[$a->status] ?? $a->status,
                    $a->lost_days ?? 0,
                    $a->iess_reported ? 'Sí' : 'No',
                    $a->at01_number ?? '-',
                ];
            }
        } else {
            $title   = 'Evaluaciones';
            $headers = ['Trabajador', 'Doc.', 'Empresa', 'Tipo', 'Fecha', 'Motivo', 'Aptitud', 'Médico'];
            $query   = OccupationalEvaluation::with(['worker:id,first_name,last_name,document_number', 'worker.company:id,business_name'])
                ->when($validated['company_id'] ?? null, fn ($q, $id) => $q->whereHas('worker', fn ($wq) => $wq->where('company_id', $id)))
                ->when($dateFrom, fn ($q) => $q->whereDate('attention_date', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('attention_date', '<=', $dateTo))
                ->orderByDesc('attention_date')
                ->cursor();
            foreach ($query as $ev) {
                $rows[] = [
                    trim(($ev->worker->first_name ?? '') . ' ' . ($ev->worker->last_name ?? '')),
                    $ev->worker?->document_number ?? '-',
                    $ev->worker?->company?->business_name ?? '-',
                    $ev->evaluation_type,
                    $ev->attention_date?->toDateString() ?? '-',
                    $ev->consultation_reason ?? '-',
                    $ev->medical_aptitude ?? '-',
                    $ev->professional_name ?? '-',
                ];
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle($title);

        // Title row
        $sheet->setCellValue('A1', "Reporte: {$title} — generado " . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers)) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        // Header row
        $col = 1;
        foreach ($headers as $h) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '2';
            $sheet->setCellValue($cell, $h);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('0F172A');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }

        // Data rows
        $rowIdx = 3;
        foreach ($rows as $row) {
            $col = 1;
            foreach ($row as $val) {
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $rowIdx, $val);
                $col++;
            }
            $rowIdx++;
        }

        // Auto-width
        foreach (range(1, count($headers)) as $i) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        $filename = "reporte-{$type}-" . now()->format('Ymd-His') . '.xlsx';
        $tmpPath  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tmpPath);

        return response()->download($tmpPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function companyDetail(Request $request, int $companyId): JsonResponse
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date'],
        ]);
        $dateFrom = $validated['date_from'] ?? null;
        $dateTo   = $validated['date_to']   ?? null;

        // Company info
        $company = \App\Models\Company::findOrFail($companyId);

        // Worker stats
        $workerCount = Worker::where('company_id', $companyId)->count();

        // Evaluations with date filter
        $evalQuery = OccupationalEvaluation::whereHas(
            'worker', fn ($q) => $q->where('company_id', $companyId)
        );
        $this->applyDateRange($evalQuery, $dateFrom, $dateTo);

        $totalEvaluations = (clone $evalQuery)->count();

        // Aptitude breakdown
        $aptDist = (clone $evalQuery)
            ->select('medical_aptitude', DB::raw('COUNT(*) as total'))
            ->groupBy('medical_aptitude')
            ->pluck('total', 'medical_aptitude');

        // Evaluation type breakdown
        $evalTypes = (clone $evalQuery)
            ->select('evaluation_type', DB::raw('COUNT(*) as total'))
            ->groupBy('evaluation_type')
            ->pluck('total', 'evaluation_type');

        // Certificates
        $certQuery = MedicalCertificate::whereHas(
            'worker', fn ($q) => $q->where('company_id', $companyId)
        );
        if ($dateFrom) $certQuery->whereDate('issue_date', '>=', $dateFrom);
        if ($dateTo)   $certQuery->whereDate('issue_date', '<=', $dateTo);
        $totalCerts = (clone $certQuery)->count();
        $expiringCerts = MedicalCertificate::whereHas('worker', fn ($q) => $q->where('company_id', $companyId))
            ->whereDate('valid_until', '<=', now()->addDays(30))
            ->whereDate('valid_until', '>=', now())
            ->count();

        // Accidents
        $accidents = \App\Models\OccupationalAccident::whereHas(
            'worker', fn ($q) => $q->where('company_id', $companyId)
        )->count();

        // Recent evaluations (last 10)
        $recentEvals = OccupationalEvaluation::with('worker:id,first_name,last_name,document_number')
            ->whereHas('worker', fn ($q) => $q->where('company_id', $companyId))
            ->orderByDesc('attention_date')
            ->limit(10)
            ->get()
            ->map(fn ($e) => [
                'id'              => $e->id,
                'worker_name'     => trim(($e->worker->first_name ?? '') . ' ' . ($e->worker->last_name ?? '')),
                'worker_document' => $e->worker->document_number ?? '-',
                'attention_date'  => $e->attention_date?->format('d/m/Y'),
                'evaluation_type' => $e->evaluation_type,
                'medical_aptitude'=> $e->medical_aptitude,
            ]);

        // Monthly trend (last 6 months)
        $driver = DB::connection()->getDriverName();
        $monthExpr = match ($driver) {
            'sqlite' => "strftime('%Y-%m', attention_date)",
            'mysql'  => "DATE_FORMAT(attention_date, '%Y-%m')",
            default  => "TO_CHAR(attention_date, 'YYYY-MM')",
        };
        $monthlyTrend = OccupationalEvaluation::selectRaw("{$monthExpr} as month_key, COUNT(*) as total")
            ->whereHas('worker', fn ($q) => $q->where('company_id', $companyId))
            ->whereDate('attention_date', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->map(fn ($r) => ['month' => $r->month_key, 'total' => (int) $r->total]);

        return response()->json([
            'ok'   => true,
            'data' => [
                'company'           => [
                    'id'            => $company->id,
                    'name'          => $company->business_name,
                    'ruc'           => $company->ruc,
                    'ciiu'          => $company->ciiu,
                    'work_center'   => $company->work_center,
                    'address'       => $company->address,
                ],
                'stats'             => [
                    'workers'          => $workerCount,
                    'evaluations'      => $totalEvaluations,
                    'certificates'     => $totalCerts,
                    'expiring_certs'   => $expiringCerts,
                    'accidents'        => $accidents,
                ],
                'aptitude_dist'     => $aptDist,
                'eval_type_dist'    => $evalTypes,
                'monthly_trend'     => $monthlyTrend,
                'recent_evals'      => $recentEvals,
            ],
        ]);
    }
}
