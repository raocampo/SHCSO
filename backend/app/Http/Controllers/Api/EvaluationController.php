<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisCatalog;
use App\Models\EvaluationAttachment;
use App\Models\EvaluationDiagnosis;
use App\Models\OccupationalEvaluation;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EvaluationController extends Controller
{
    private const ATTACHMENT_TYPES = [
        'GENERAL',
        'LAB_EXAM',
        'IMAGING',
        'DICOM',
        'AUDIO',
        'OTHER',
    ];

    private const ALLOWED_ATTACHMENT_EXTENSIONS = [
        'pdf',
        'jpg',
        'jpeg',
        'png',
        'dcm',
        'dicom',
        'ima',
        'zip',
    ];

    private const DICOM_EXTENSIONS = [
        'dcm',
        'dicom',
        'ima',
        'zip',
    ];

    private function mapAttachment(EvaluationAttachment $attachment): array
    {
        return [
            ...$attachment->toArray(),
            'file_url' => Storage::disk('public')->url($attachment->file_path),
            'download_path' => "/api/evaluations/attachments/{$attachment->id}/download",
        ];
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'worker_id' => ['nullable', 'uuid', 'exists:workers,id'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'evaluation_type' => ['nullable', 'in:INGRESO,PERIODICO,REINTEGRO,RETIRO'],
            'medical_aptitude' => ['nullable', 'in:APTO,APTO_OBSERVACION,APTO_LIMITACIONES,NO_APTO'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'q' => ['nullable', 'string', 'max:120'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? $validated['limit'] ?? 20);
        $page = (int) ($validated['page'] ?? 1);
        $searchTerm = isset($validated['q']) ? mb_strtolower(trim($validated['q'])) : null;

        $query = OccupationalEvaluation::query()
            ->with([
                'worker:id,first_name,last_name,document_number,company_id',
                'worker.company:id,business_name',
            ])
            ->orderByDesc('attention_date')
            ->orderByDesc('created_at');

        $query->when(
            isset($validated['worker_id']),
            fn ($builder) => $builder->where('worker_id', $validated['worker_id'])
        );
        $query->when(
            isset($validated['evaluation_type']),
            fn ($builder) => $builder->where('evaluation_type', $validated['evaluation_type'])
        );
        $query->when(
            isset($validated['medical_aptitude']),
            fn ($builder) => $builder->where('medical_aptitude', $validated['medical_aptitude'])
        );
        $query->when(
            isset($validated['date_from']),
            fn ($builder) => $builder->whereDate('attention_date', '>=', $validated['date_from'])
        );
        $query->when(
            isset($validated['date_to']),
            fn ($builder) => $builder->whereDate('attention_date', '<=', $validated['date_to'])
        );
        $query->when(
            isset($validated['company_id']),
            fn ($builder) => $builder->whereHas('worker', fn ($workerQuery) => $workerQuery->where('company_id', $validated['company_id']))
        );
        $query->when($searchTerm, function ($builder) use ($searchTerm) {
            $builder->whereHas('worker', function ($workerQuery) use ($searchTerm) {
                $workerQuery
                    ->whereRaw('LOWER(document_number) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ["%{$searchTerm}%"]);
            });
        });

        $total = (clone $query)->count();
        $evaluations = $query
            ->forPage($page, $perPage)
            ->get();
        $totalPages = max(1, (int) ceil($total / $perPage));

        return response()->json([
            'ok' => true,
            'data' => $evaluations,
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
            'worker_id' => ['required', 'uuid', 'exists:workers,id'],
            'evaluation_type' => ['required', 'in:INGRESO,PERIODICO,REINTEGRO,RETIRO'],
            'attention_date' => ['nullable', 'date'],
            'consultation_reason' => ['required', 'string', 'min:5'],
            'personal_background' => ['nullable', 'array'],
            'current_problem' => ['nullable', 'string'],
            'vital_signs' => ['nullable', 'array'],
            'physical_exam' => ['nullable', 'array'],
            'risk_factors' => ['nullable', 'array'],
            'labor_activity_history' => ['nullable', 'array'],
            'extra_activities' => ['nullable', 'array'],
            'exam_results' => ['nullable', 'array'],
            'medical_aptitude' => ['required', 'in:APTO,APTO_OBSERVACION,APTO_LIMITACIONES,NO_APTO'],
            'restrictions' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],
            'retirement_notes' => ['nullable', 'string'],
            'professional_name' => ['required', 'string', 'min:3', 'max:150'],
            'professional_code' => ['required', 'string', 'min:3', 'max:60'],
            'worker_signature_path' => ['nullable', 'string'],
            'diagnoses' => ['nullable', 'array'],
            'diagnoses.*.code' => ['required_with:diagnoses', 'string', 'max:12'],
            'diagnoses.*.description' => ['nullable', 'string', 'max:400'],
            'diagnoses.*.diagnosis_type' => ['required_with:diagnoses', 'in:PRE,DEF'],
            'diagnoses.*.notes' => ['nullable', 'string', 'max:500'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $evaluation = DB::transaction(function () use ($validated, $user) {
            $evaluation = new OccupationalEvaluation([
                'worker_id' => $validated['worker_id'],
                'evaluator_user_id' => $user->id,
                'evaluation_type' => $validated['evaluation_type'],
                'attention_date' => $validated['attention_date'] ?? now()->toDateString(),
                'consultation_reason' => $validated['consultation_reason'],
                'personal_background' => $validated['personal_background'] ?? [],
                'current_problem' => $validated['current_problem'] ?? null,
                'vital_signs' => $validated['vital_signs'] ?? [],
                'physical_exam' => $validated['physical_exam'] ?? [],
                'risk_factors' => $validated['risk_factors'] ?? [],
                'labor_activity_history' => $validated['labor_activity_history'] ?? [],
                'extra_activities' => $validated['extra_activities'] ?? [],
                'exam_results' => $validated['exam_results'] ?? [],
                'medical_aptitude' => $validated['medical_aptitude'],
                'restrictions' => $validated['restrictions'] ?? null,
                'recommendations' => $validated['recommendations'] ?? null,
                'retirement_notes' => $validated['retirement_notes'] ?? null,
                'professional_name' => $validated['professional_name'],
                'professional_code' => $validated['professional_code'],
                'worker_signature_path' => $validated['worker_signature_path'] ?? null,
            ]);
            $evaluation->id = (string) Str::uuid();
            $evaluation->save();

            foreach (($validated['diagnoses'] ?? []) as $diagnosis) {
                DiagnosisCatalog::query()->updateOrCreate(
                    ['code' => $diagnosis['code']],
                    ['description' => $diagnosis['description'] ?? "Diagnostico {$diagnosis['code']}"]
                );

                EvaluationDiagnosis::query()->create([
                    'evaluation_id' => $evaluation->id,
                    'diagnosis_code' => $diagnosis['code'],
                    'diagnosis_type' => $diagnosis['diagnosis_type'],
                    'notes' => $diagnosis['notes'] ?? null,
                ]);
            }

            return $evaluation;
        });

        AuditLogger::log(
            $user,
            'CREATE_EVALUATION',
            'occupational_evaluation',
            $evaluation->id,
            [
                'worker_id' => $validated['worker_id'],
                'medical_aptitude' => $validated['medical_aptitude'],
            ]
        );

        return response()->json([
            'ok' => true,
            'data' => $evaluation,
        ], 201);
    }

    public function uploadAttachment(Request $request, string $evaluationId): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200'],
            'attachment_type' => ['nullable', 'in:' . implode(',', self::ATTACHMENT_TYPES)],
            'exam_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $evaluation = OccupationalEvaluation::query()->findOrFail($evaluationId);
        $file = $request->file('file');
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $attachmentType = strtoupper((string) ($request->input('attachment_type') ?: 'GENERAL'));

        if (!in_array($extension, self::ALLOWED_ATTACHMENT_EXTENSIONS, true)) {
            throw ValidationException::withMessages([
                'file' => [
                    'Formato no permitido. Usa PDF, JPG, PNG, DCM, DICOM, IMA o ZIP.',
                ],
            ]);
        }

        if ($attachmentType === 'DICOM' && !in_array($extension, self::DICOM_EXTENSIONS, true)) {
            throw ValidationException::withMessages([
                'file' => [
                    'Para tipo DICOM usa archivos .dcm, .dicom, .ima o .zip.',
                ],
            ]);
        }

        $categoryFolder = strtolower($attachmentType);
        $storedPath = $file->store("evaluation-attachments/{$evaluation->id}/{$categoryFolder}", 'public');
        $examDate = trim((string) $request->input('exam_date'));
        $notes = trim((string) $request->input('notes'));

        $attachment = EvaluationAttachment::query()->create([
            'evaluation_id' => $evaluation->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $storedPath,
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'attachment_type' => $attachmentType,
            'exam_date' => $examDate !== '' ? $examDate : null,
            'notes' => $notes !== '' ? $notes : null,
            'file_size_bytes' => $file->getSize(),
            'original_extension' => $extension,
            'uploaded_by' => $request->user()?->id,
        ]);

        AuditLogger::log(
            $request->user(),
            'UPLOAD_EVALUATION_ATTACHMENT',
            'evaluation_attachment',
            (string) $attachment->id,
            ['evaluation_id' => $evaluation->id]
        );

        return response()->json([
            'ok' => true,
            'data' => $this->mapAttachment($attachment),
        ], 201);
    }

    public function listAttachments(string $evaluationId): JsonResponse
    {
        $evaluation = OccupationalEvaluation::query()->findOrFail($evaluationId);

        $attachments = $evaluation->attachments()
            ->latest()
            ->get()
            ->map(fn ($attachment) => $this->mapAttachment($attachment));

        return response()->json([
            'ok' => true,
            'data' => $attachments,
        ]);
    }

    public function downloadAttachment(Request $request, string $attachmentId): StreamedResponse
    {
        $attachment = EvaluationAttachment::query()->findOrFail($attachmentId);
        $disk = Storage::disk('public');

        if (!$disk->exists($attachment->file_path)) {
            abort(404, 'Archivo no encontrado.');
        }

        AuditLogger::log(
            $request->user(),
            'DOWNLOAD_EVALUATION_ATTACHMENT',
            'evaluation_attachment',
            (string) $attachment->id,
            ['evaluation_id' => $attachment->evaluation_id]
        );

        return $disk->download($attachment->file_path, $attachment->file_name);
    }

    public function show(string $evaluationId): JsonResponse
    {
        $evaluation = OccupationalEvaluation::query()
            ->with([
                'worker:id,first_name,last_name,document_number',
                'diagnoses:id,evaluation_id,diagnosis_code,diagnosis_type,notes',
                'diagnoses.diagnosisCatalog:code,description',
                'attachments:id,evaluation_id,file_name,file_path,mime_type,attachment_type,exam_date,notes,file_size_bytes,original_extension,uploaded_by,created_at',
            ])
            ->findOrFail($evaluationId);

        $attachments = $evaluation->attachments->map(fn ($attachment) => $this->mapAttachment($attachment));

        return response()->json([
            'ok' => true,
            'data' => [
                ...$evaluation->toArray(),
                'attachments' => $attachments,
            ],
        ]);
    }
}
