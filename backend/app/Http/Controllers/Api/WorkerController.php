<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\OccupationalEvaluation;
use App\Models\Worker;
use App\Models\WorkerClinicalHistory;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class WorkerController extends Controller
{
    private function buildCode(string $prefix): string
    {
        return sprintf('%s-%s-%s', $prefix, now()->format('His'), random_int(10, 99));
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = trim((string) ($validated['q'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? $validated['limit'] ?? 20);
        $page = (int) ($validated['page'] ?? 1);

        $workersQuery = Worker::query()
            ->with(['company:id,business_name', 'jobPosition:id,name'])
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where('document_number', 'like', "%{$query}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE ?", ["%{$query}%"]);
            })
            ->latest();

        $total = (clone $workersQuery)->count();
        $workers = $workersQuery
            ->forPage($page, $perPage)
            ->get();

        $totalPages = max(1, (int) ceil($total / $perPage));

        return response()->json([
            'ok' => true,
            'data' => $workers,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1,
            ],
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

    public function destroy(Request $request, string $workerId): JsonResponse
    {
        $worker = Worker::query()->findOrFail($workerId);
        $documentNumber = $worker->document_number;

        $worker->delete();

        AuditLogger::log(
            $request->user(),
            'DELETE_WORKER',
            'worker',
            $workerId,
            ['document_number' => $documentNumber]
        );

        return response()->json([
            'ok' => true,
            'message' => 'Trabajador eliminado correctamente.',
        ]);
    }

    public function clinicalHistory(string $workerId): JsonResponse
    {
        $worker = Worker::query()->findOrFail($workerId);

        $clinicalHistory = WorkerClinicalHistory::query()
            ->where('worker_id', $worker->id)
            ->first();

        return response()->json([
            'ok' => true,
            'data' => $clinicalHistory ?? [
                'worker_id' => $worker->id,
                'personal_background' => null,
                'family_background' => null,
                'allergies' => null,
                'current_medication' => null,
                'pathological_history' => null,
                'surgical_history' => null,
                'occupational_history' => null,
                'lifestyle_notes' => null,
                'longitudinal_notes' => null,
            ],
        ]);
    }

    public function upsertClinicalHistory(Request $request, string $workerId): JsonResponse
    {
        $worker = Worker::query()->findOrFail($workerId);

        $validated = $request->validate([
            'personal_background' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'family_background' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'allergies' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'current_medication' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'pathological_history' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'surgical_history' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'occupational_history' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'lifestyle_notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'longitudinal_notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ]);

        $clinicalHistory = WorkerClinicalHistory::query()->updateOrCreate(
            ['worker_id' => $worker->id],
            $validated
        );

        AuditLogger::log(
            $request->user(),
            'UPDATE_WORKER_CLINICAL_HISTORY',
            'worker',
            $worker->id,
            ['updated_fields' => array_keys($validated)]
        );

        return response()->json([
            'ok' => true,
            'data' => $clinicalHistory,
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
                'attachments:id,evaluation_id,file_name,file_path,mime_type,attachment_type,exam_date,notes,file_size_bytes,original_extension,created_at',
                'prescriptions:id,evaluation_id,medication,dosage,frequency,duration,indications,created_at',
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

        $clinicalHistory = WorkerClinicalHistory::query()
            ->where('worker_id', $worker->id)
            ->first();

        $clinicalTimeline = $evaluations
            ->map(function (OccupationalEvaluation $evaluation) {
                return [
                    'event_type' => 'EVALUATION',
                    'event_date' => $evaluation->attention_date?->toDateString(),
                    'reference_id' => $evaluation->id,
                    'title' => 'Evaluacion ' . $evaluation->evaluation_type,
                    'subtitle' => $evaluation->medical_aptitude,
                    'notes' => $evaluation->consultation_reason,
                    'created_at' => $evaluation->created_at?->toISOString(),
                ];
            })
            ->concat(
                $certificates->map(function (MedicalCertificate $certificate) {
                    return [
                        'event_type' => 'CERTIFICATE',
                        'event_date' => $certificate->issue_date?->toDateString(),
                        'reference_id' => $certificate->id,
                        'title' => 'Certificado ' . $certificate->certificate_code,
                        'subtitle' => $certificate->medical_aptitude,
                        'notes' => $certificate->observations,
                        'created_at' => $certificate->created_at?->toISOString(),
                    ];
                })
            )
            ->sortByDesc(fn (array $event) => sprintf('%s|%s', $event['event_date'] ?? '', $event['created_at'] ?? ''))
            ->values();

        return response()->json([
            'ok' => true,
            'data' => [
                'worker' => $worker,
                'clinical_history' => $clinicalHistory,
                'clinical_timeline' => $clinicalTimeline,
                'evaluations' => $evaluations,
                'certificates' => $certificates,
            ],
        ]);
    }

    public function allAttachments(string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $attachments = \App\Models\EvaluationAttachment::query()
            ->whereHas('evaluation', fn ($q) => $q->where('worker_id', $workerId))
            ->with('evaluation:id,attention_date,evaluation_type')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($att) {
                return [
                    'id'                 => $att->id,
                    'evaluation_id'      => $att->evaluation_id,
                    'evaluation_date'    => $att->evaluation?->attention_date?->toDateString(),
                    'evaluation_type'    => $att->evaluation?->evaluation_type,
                    'file_name'          => $att->file_name,
                    'mime_type'          => $att->mime_type,
                    'attachment_type'    => $att->attachment_type,
                    'exam_date'          => $att->exam_date?->toDateString(),
                    'notes'              => $att->notes,
                    'file_size_bytes'    => $att->file_size_bytes,
                    'original_extension' => $att->original_extension,
                    'file_url'           => \Illuminate\Support\Facades\Storage::disk('public')->url($att->file_path),
                    'download_path'      => "/api/evaluations/attachments/{$att->id}/download",
                    'created_at'         => $att->created_at?->toIso8601String(),
                ];
            });

        return response()->json(['ok' => true, 'data' => $attachments]);
    }

    public function historyPdf(string $workerId): Response
    {
        $worker = Worker::query()
            ->with(['company:id,business_name,ruc', 'jobPosition:id,name'])
            ->findOrFail($workerId);

        $clinicalHistory = WorkerClinicalHistory::query()
            ->where('worker_id', $workerId)
            ->first();

        $evaluations = OccupationalEvaluation::query()
            ->with([
                'diagnoses.diagnosisCatalog:code,description',
                'prescriptions:id,evaluation_id,medication,dosage,frequency,duration,indications',
            ])
            ->where('worker_id', $workerId)
            ->orderByDesc('attention_date')
            ->get();

        $certificates = MedicalCertificate::query()
            ->where('worker_id', $workerId)
            ->orderByDesc('issue_date')
            ->get();

        $vaccinations = \App\Models\WorkerVaccination::query()
            ->where('worker_id', $workerId)
            ->orderByDesc('vaccination_date')
            ->get();

        $accidents = \App\Models\OccupationalAccident::query()
            ->where('worker_id', $workerId)
            ->orderByDesc('accident_date')
            ->get();

        $institution = \App\Models\SystemSetting::institutionConfig();

        $pdf = Pdf::loadView('pdf.worker-history', compact(
            'worker',
            'clinicalHistory',
            'evaluations',
            'certificates',
            'vaccinations',
            'accidents',
            'institution'
        ))->setPaper('a4', 'portrait');

        $filename = 'HC-' . strtoupper(substr($workerId, 0, 8)) . '.pdf';
        return $pdf->download($filename);
    }
}
