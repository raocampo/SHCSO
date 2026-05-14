<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\DiagnosisCatalog;
use App\Models\JobPosition;
use App\Models\Medication;
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

    public function updateCompany(Request $request, int $companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);

        $validated = $request->validate([
            'ruc'           => ['nullable', 'string', 'max:13', 'unique:companies,ruc,' . $companyId],
            'ciiu'          => ['nullable', 'string', 'max:12'],
            'business_name' => ['required', 'string', 'min:3', 'max:180'],
            'work_center'   => ['nullable', 'string', 'max:180'],
            'address'       => ['nullable', 'string', 'max:500'],
        ]);

        $company->update($validated);

        AuditLogger::log($request->user(), 'UPDATE_COMPANY', 'company', (string) $company->id);

        return response()->json(['ok' => true, 'data' => $company]);
    }

    public function deleteCompany(Request $request, int $companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);

        // Prevent deletion if company has workers assigned
        $workerCount = \App\Models\Worker::where('company_id', $companyId)->count();
        if ($workerCount > 0) {
            return response()->json([
                'ok'      => false,
                'message' => "No se puede eliminar: la empresa tiene {$workerCount} trabajador(es) asignado(s). Reasigne o elimine los trabajadores primero.",
            ], 422);
        }

        AuditLogger::log($request->user(), 'DELETE_COMPANY', 'company', (string) $company->id);
        $company->delete();

        return response()->json(['ok' => true, 'message' => 'Empresa eliminada correctamente.']);
    }

    public function listJobPositions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'level' => ['nullable', 'integer', 'min:1', 'max:6'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:3500'],
        ]);

        $query = mb_strtolower(trim((string) ($validated['q'] ?? '')));
        $level = $validated['level'] ?? null;
        $limit = (int) ($validated['limit'] ?? 2500);

        return response()->json([
            'ok' => true,
            'data' => JobPosition::query()
                ->whereNotNull('ciiu_code')
                ->when($query !== '', function ($builder) use ($query) {
                    $builder->where(function ($q) use ($query) {
                        $q->whereRaw('LOWER(COALESCE(ciiu_code, ciuo_code, \'\')) LIKE ?', ["%{$query}%"])
                            ->orWhereRaw('LOWER(name) LIKE ?', ["%{$query}%"]);
                    });
                })
                ->when($level !== null, fn ($builder) => $builder->where('ciiu_level', $level))
                ->orderByRaw('COALESCE(ciiu_code, ciuo_code, \'\') ASC')
                ->orderBy('name')
                ->limit($limit)
                ->get(),
        ]);
    }

    public function createJobPosition(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ciiu_code' => ['nullable', 'string', 'max:12'],
            'ciuo_code' => ['nullable', 'string', 'max:12'],
            'ciiu_level' => ['nullable', 'integer', 'min:1', 'max:6'],
            'name' => ['required', 'string', 'min:3', 'max:160'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        if (empty($validated['ciuo_code']) && ! empty($validated['ciiu_code'])) {
            $validated['ciuo_code'] = $validated['ciiu_code'];
        }

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

    public function listMedications(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q'     => ['nullable', 'string', 'max:120'],
            'group' => ['nullable', 'string', 'max:150'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $q     = mb_strtolower(trim((string) ($validated['q'] ?? '')));
        $group = $validated['group'] ?? null;
        $limit = (int) ($validated['limit'] ?? 15);

        $medications = Medication::active()
            ->when($q !== '', function ($builder) use ($q) {
                $builder->whereRaw('LOWER(generic_name) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(commercial_name) LIKE ?', ["%{$q}%"]);
            })
            ->when($group, fn ($b) => $b->where('therapeutic_group', $group))
            ->orderBy('generic_name')
            ->limit($limit)
            ->get(['id', 'generic_name', 'commercial_name', 'concentration', 'pharmaceutical_form', 'therapeutic_group', 'via_administracion', 'controlled']);

        return response()->json(['ok' => true, 'data' => $medications]);
    }

    public function storeMedication(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code'                => ['nullable', 'string', 'max:30', 'unique:medications,code'],
            'generic_name'        => ['required', 'string', 'max:200'],
            'commercial_name'     => ['nullable', 'string', 'max:200'],
            'concentration'       => ['nullable', 'string', 'max:100'],
            'pharmaceutical_form' => ['nullable', 'string', 'max:100'],
            'therapeutic_group'   => ['nullable', 'string', 'max:150'],
            'via_administracion'  => ['nullable', 'string', 'max:80'],
            'controlled'          => ['nullable', 'boolean'],
        ]);

        $medication = Medication::create($validated);

        return response()->json(['ok' => true, 'data' => $medication], 201);
    }

    public function updateMedication(Request $request, int $medicationId): JsonResponse
    {
        $medication = Medication::findOrFail($medicationId);

        $validated = $request->validate([
            'generic_name'        => ['nullable', 'string', 'max:200'],
            'commercial_name'     => ['nullable', 'string', 'max:200'],
            'concentration'       => ['nullable', 'string', 'max:100'],
            'pharmaceutical_form' => ['nullable', 'string', 'max:100'],
            'therapeutic_group'   => ['nullable', 'string', 'max:150'],
            'via_administracion'  => ['nullable', 'string', 'max:80'],
            'controlled'          => ['nullable', 'boolean'],
            'active'              => ['nullable', 'boolean'],
        ]);

        $medication->update($validated);

        return response()->json(['ok' => true, 'data' => $medication]);
    }
}
