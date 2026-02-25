<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\DiagnosisCatalog;
use App\Models\JobPosition;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function listCompanies(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'data' => Company::query()->latest()->limit(200)->get(),
        ]);
    }

    public function createCompany(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ruc' => ['nullable', 'string', 'max:13', 'unique:companies,ruc'],
            'ciiu' => ['nullable', 'string', 'max:12'],
            'business_name' => ['required', 'string', 'min:3', 'max:180'],
            'work_center' => ['nullable', 'string', 'max:180'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $company = Company::create($validated);

        AuditLogger::log(
            $request->user(),
            'CREATE_COMPANY',
            'company',
            (string) $company->id
        );

        return response()->json([
            'ok' => true,
            'data' => $company,
        ], 201);
    }

    public function listJobPositions(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'data' => JobPosition::query()->latest()->limit(200)->get(),
        ]);
    }

    public function createJobPosition(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ciuo_code' => ['nullable', 'string', 'max:12'],
            'name' => ['required', 'string', 'min:3', 'max:160'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $jobPosition = JobPosition::create($validated);

        AuditLogger::log(
            $request->user(),
            'CREATE_JOB_POSITION',
            'job_position',
            (string) $jobPosition->id
        );

        return response()->json([
            'ok' => true,
            'data' => $jobPosition,
        ], 201);
    }

    public function listDiagnoses(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = mb_strtolower(trim((string) ($validated['q'] ?? '')));
        $limit = (int) ($validated['limit'] ?? 12);

        $diagnoses = DiagnosisCatalog::query()
            ->when($query !== '', function ($builder) use ($query) {
                $builder->whereRaw('LOWER(code) LIKE ?', ["%{$query}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$query}%"]);
            })
            ->orderBy('code')
            ->limit($limit)
            ->get(['code', 'description']);

        return response()->json([
            'ok' => true,
            'data' => $diagnoses,
        ]);
    }
}
