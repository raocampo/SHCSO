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
}
