<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\OccupationalEvaluation;
use App\Models\Worker;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WorkerController extends Controller
{
    private function buildCode(string $prefix): string
    {
        return sprintf('%s-%s-%s', $prefix, now()->format('His'), random_int(10, 99));
    }

    public function index(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));
        $limit = min(max((int) $request->query('limit', 20), 1), 100);

        $workers = Worker::query()
            ->with(['company:id,business_name', 'jobPosition:id,name'])
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where('document_number', 'like', "%{$query}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE ?", ["%{$query}%"]);
            })
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $workers,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'min:2', 'max:30'],
            'document_number' => ['required', 'string', 'min:5', 'max:30', 'unique:workers,document_number'],
            'first_name' => ['required', 'string', 'min:2', 'max:120'],
            'last_name' => ['required', 'string', 'min:2', 'max:120'],
            'birth_date' => ['required', 'date'],
            'sex' => ['required', 'in:M,F,O'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:30'],
            'blood_type' => ['nullable', 'string', 'max:10'],
            'laterality' => ['nullable', 'string', 'max:15'],
            'is_pregnant' => ['nullable', 'boolean'],
            'has_disability' => ['nullable', 'boolean'],
            'catastrophic_disease' => ['nullable', 'boolean'],
            'is_elderly' => ['nullable', 'boolean'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'job_position_id' => ['nullable', 'integer', 'exists:job_positions,id'],
        ]);

        $worker = new Worker($validated);
        $worker->id = (string) Str::uuid();
        $worker->history_number = $this->buildCode('HC');
        $worker->file_number = $this->buildCode('AR');
        $worker->save();

        AuditLogger::log(
            $request->user(),
            'CREATE_WORKER',
            'worker',
            $worker->id,
            ['document_number' => $worker->document_number]
        );

        return response()->json([
            'ok' => true,
            'data' => $worker->load(['company:id,business_name', 'jobPosition:id,name']),
        ], 201);
    }

    public function show(string $workerId): JsonResponse
    {
        $worker = Worker::query()
            ->with(['company:id,business_name', 'jobPosition:id,name'])
            ->findOrFail($workerId);

        return response()->json([
            'ok' => true,
            'data' => $worker,
        ]);
    }

    public function update(Request $request, string $workerId): JsonResponse
    {
        $worker = Worker::query()->findOrFail($workerId);

        $validated = $request->validate([
            'document_type' => ['sometimes', 'string', 'min:2', 'max:30'],
            'document_number' => [
                'sometimes',
                'string',
                'min:5',
                'max:30',
                Rule::unique('workers', 'document_number')->ignore($worker->id),
            ],
            'first_name' => ['sometimes', 'string', 'min:2', 'max:120'],
            'last_name' => ['sometimes', 'string', 'min:2', 'max:120'],
            'birth_date' => ['sometimes', 'date'],
            'sex' => ['sometimes', 'in:M,F,O'],
            'email' => ['sometimes', 'nullable', 'email', 'max:160'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'blood_type' => ['sometimes', 'nullable', 'string', 'max:10'],
            'laterality' => ['sometimes', 'nullable', 'string', 'max:15'],
            'is_pregnant' => ['sometimes', 'nullable', 'boolean'],
            'has_disability' => ['sometimes', 'nullable', 'boolean'],
            'catastrophic_disease' => ['sometimes', 'nullable', 'boolean'],
            'is_elderly' => ['sometimes', 'nullable', 'boolean'],
            'company_id' => ['sometimes', 'nullable', 'integer', 'exists:companies,id'],
            'job_position_id' => ['sometimes', 'nullable', 'integer', 'exists:job_positions,id'],
        ]);

        $worker->fill($validated);
        $worker->save();

        AuditLogger::log(
            $request->user(),
            'UPDATE_WORKER',
            'worker',
            $worker->id
        );

        return response()->json([
            'ok' => true,
            'data' => $worker->load(['company:id,business_name', 'jobPosition:id,name']),
        ]);
    }

    public function history(string $workerId): JsonResponse
    {
        $worker = Worker::query()
            ->with(['company:id,business_name', 'jobPosition:id,name'])
            ->findOrFail($workerId);

        $evaluations = OccupationalEvaluation::query()
            ->with([
                'diagnoses:id,evaluation_id,diagnosis_code,diagnosis_type,notes',
                'diagnoses.diagnosisCatalog:code,description',
                'attachments:id,evaluation_id,file_name,file_path,mime_type,created_at',
            ])
            ->where('worker_id', $worker->id)
            ->orderByDesc('attention_date')
            ->orderByDesc('created_at')
            ->get();

        $certificates = MedicalCertificate::query()
            ->where('worker_id', $worker->id)
            ->orderByDesc('issue_date')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => [
                'worker' => $worker,
                'evaluations' => $evaluations,
                'certificates' => $certificates,
            ],
        ]);
    }
}
