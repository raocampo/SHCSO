<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\OccupationalEvaluation;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    private function buildCertificateCode(): string
    {
        return sprintf('CERT-%s-%s', now()->format('Ymd'), random_int(100000, 999999));
    }

    public function storeFromEvaluation(Request $request, string $evaluationId): JsonResponse
    {
        $validated = $request->validate([
            'issue_date'            => ['nullable', 'date'],
            'valid_until'           => ['nullable', 'date', 'after:issue_date'],
            'observations'          => ['nullable', 'string'],
            'recommendations'       => ['nullable', 'string'],
            'worker_signature_path' => ['nullable', 'string'],
            'pdf_path'              => ['nullable', 'string'],
            'qr_code_data'          => ['nullable', 'string'],
        ]);

        $evaluation = OccupationalEvaluation::query()->findOrFail($evaluationId);

        $issueDate  = $validated['issue_date'] ?? now()->toDateString();
        $validUntil = $validated['valid_until'] ?? \Carbon\Carbon::parse($issueDate)->addYear()->toDateString();

        $certificate = new MedicalCertificate([
            'certificate_code'      => $this->buildCertificateCode(),
            'evaluation_id'         => $evaluation->id,
            'worker_id'             => $evaluation->worker_id,
            'issue_date'            => $issueDate,
            'valid_until'           => $validUntil,
            'medical_aptitude'      => $evaluation->medical_aptitude,
            'observations'          => $validated['observations'] ?? null,
            'recommendations'       => $validated['recommendations'] ?? $evaluation->recommendations,
            'professional_name'     => $evaluation->professional_name,
            'professional_code'     => $evaluation->professional_code,
            'worker_signature_path' => $validated['worker_signature_path'] ?? null,
            'pdf_path'              => $validated['pdf_path'] ?? null,
            'qr_code_data'          => $validated['qr_code_data'] ?? null,
            'created_by'            => $request->user()?->id,
        ]);
        $certificate->id = (string) Str::uuid();
        $certificate->save();

        AuditLogger::log(
            $request->user(),
            'CREATE_CERTIFICATE',
            'medical_certificate',
            $certificate->id,
            ['evaluation_id' => $evaluation->id]
        );

        return response()->json([
            'ok' => true,
            'data' => $certificate,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'worker_id' => ['nullable', 'uuid', 'exists:workers,id'],
            'evaluation_id' => ['nullable', 'uuid', 'exists:occupational_evaluations,id'],
            'medical_aptitude' => ['nullable', 'in:APTO,APTO_OBSERVACION,APTO_LIMITACIONES,NO_APTO'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? $validated['limit'] ?? 20);
        $page = (int) ($validated['page'] ?? 1);

        $query = MedicalCertificate::query()
            ->with([
                'worker:id,first_name,last_name,document_number',
                'evaluation:id,evaluation_type,attention_date',
            ])
            ->latest('issue_date')
            ->latest('created_at');

        $query->when(
            isset($validated['worker_id']),
            fn ($builder) => $builder->where('worker_id', $validated['worker_id'])
        );
        $query->when(
            isset($validated['evaluation_id']),
            fn ($builder) => $builder->where('evaluation_id', $validated['evaluation_id'])
        );
        $query->when(
            isset($validated['medical_aptitude']),
            fn ($builder) => $builder->where('medical_aptitude', $validated['medical_aptitude'])
        );
        $query->when(
            isset($validated['date_from']),
            fn ($builder) => $builder->whereDate('issue_date', '>=', $validated['date_from'])
        );
        $query->when(
            isset($validated['date_to']),
            fn ($builder) => $builder->whereDate('issue_date', '<=', $validated['date_to'])
        );

        $total = (clone $query)->count();
        $certificates = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(function (MedicalCertificate $certificate) {
                return [
                    ...$certificate->toArray(),
                    'pdf_url' => $certificate->pdf_path ? Storage::disk('public')->url($certificate->pdf_path) : null,
                ];
            });
        $totalPages = max(1, (int) ceil($total / $perPage));

        return response()->json([
            'ok' => true,
            'data' => $certificates,
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

    private function resolvePublicAssetPath(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $relativePath), '/');
        $fullPath = public_path($normalizedPath);

        return is_file($fullPath) ? $fullPath : null;
    }

    private function certificateInstitutionData(): array
    {
        return [
            'name' => config('shcso.institution.name'),
            'subtitle' => config('shcso.institution.subtitle'),
            'city' => config('shcso.institution.city'),
            'footer_note' => config('shcso.pdf_certificate.footer_note'),
            'signature_name' => config('shcso.pdf_certificate.signature_name'),
            'signature_title' => config('shcso.pdf_certificate.signature_title'),
            'logo_path' => $this->resolvePublicAssetPath(config('shcso.pdf_certificate.logo_path')),
            'seal_path' => $this->resolvePublicAssetPath(config('shcso.pdf_certificate.seal_path')),
            'signature_path' => $this->resolvePublicAssetPath(config('shcso.pdf_certificate.signature_path')),
        ];
    }

    private function ensurePdfGenerated(MedicalCertificate $certificate): string
    {
        $disk = Storage::disk('public');

        if ($certificate->pdf_path && $disk->exists($certificate->pdf_path)) {
            return $certificate->pdf_path;
        }

        $certificate->loadMissing([
            'worker:id,history_number,file_number,document_number,first_name,last_name,sex',
            'worker.company:id,business_name,ruc,ciiu,work_center',
            'evaluation:id,evaluation_type,attention_date,consultation_reason,recommendations',
        ]);

        $path = "certificates/{$certificate->certificate_code}.pdf";
        $pdf = Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate,
            'institution' => $this->certificateInstitutionData(),
        ])->setPaper('a4');

        $disk->put($path, $pdf->output());

        $certificate->pdf_path = $path;
        $certificate->save();

        return $path;
    }

    public function generatePdf(Request $request, string $certificateId): JsonResponse
    {
        $certificate = MedicalCertificate::query()->findOrFail($certificateId);
        $path = $this->ensurePdfGenerated($certificate);

        AuditLogger::log(
            $request->user(),
            'GENERATE_CERTIFICATE_PDF',
            'medical_certificate',
            $certificate->id
        );

        return response()->json([
            'ok' => true,
            'data' => [
                'certificate_id' => $certificate->id,
                'certificate_code' => $certificate->certificate_code,
                'pdf_path' => $path,
                'pdf_url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    public function downloadPdf(string $certificateId): StreamedResponse
    {
        $certificate = MedicalCertificate::query()->findOrFail($certificateId);
        $path = $this->ensurePdfGenerated($certificate);

        return Storage::disk('public')->download(
            $path,
            "{$certificate->certificate_code}.pdf"
        );
    }

    public function show(string $certificateId): JsonResponse
    {
        $certificate = MedicalCertificate::query()
            ->with(['worker:id,first_name,last_name,document_number'])
            ->findOrFail($certificateId);

        return response()->json([
            'ok' => true,
            'data' => [
                ...$certificate->toArray(),
                'pdf_url' => $certificate->pdf_path ? Storage::disk('public')->url($certificate->pdf_path) : null,
            ],
        ]);
    }

    public function expiring(Request $request): JsonResponse
    {
        $days  = max(1, min(90, (int) ($request->query('days', 30))));
        $today = now()->toDateString();
        $limit = now()->addDays($days)->toDateString();

        $certificates = MedicalCertificate::query()
            ->with(['worker:id,first_name,last_name,document_number', 'worker.company:id,business_name'])
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '>=', $today)
            ->whereDate('valid_until', '<=', $limit)
            ->orderBy('valid_until')
            ->limit(100)
            ->get()
            ->map(function (MedicalCertificate $c) use ($today) {
                $daysLeft = now()->diffInDays($c->valid_until, false);
                return [
                    'id'               => $c->id,
                    'certificate_code' => $c->certificate_code,
                    'issue_date'       => $c->issue_date?->toDateString(),
                    'valid_until'      => $c->valid_until?->toDateString(),
                    'days_left'        => (int) $daysLeft,
                    'medical_aptitude' => $c->medical_aptitude,
                    'worker'           => $c->worker ? [
                        'id'              => $c->worker->id,
                        'full_name'       => trim($c->worker->first_name . ' ' . $c->worker->last_name),
                        'document_number' => $c->worker->document_number,
                        'company'         => $c->worker->company?->business_name,
                    ] : null,
                ];
            });

        return response()->json(['ok' => true, 'data' => $certificates, 'meta' => ['days' => $days, 'count' => $certificates->count()]]);
    }
}
