<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\OccupationalAccident;
use App\Models\Worker;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccidentController extends Controller
{
    private const ACCIDENT_TYPES  = ['ACCIDENT', 'NEAR_MISS', 'OCCUPATIONAL_DISEASE', 'COMMUTING'];
    private const SEVERITIES      = ['MINOR', 'MODERATE', 'SERIOUS', 'FATAL'];
    private const STATUSES        = ['OPEN', 'INVESTIGATING', 'CLOSED'];

    public function index(string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $accidents = OccupationalAccident::where('worker_id', $workerId)
            ->with('reportedBy:id,name')
            ->orderByDesc('accident_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn ($a) => $this->mapAccident($a));

        return response()->json(['ok' => true, 'data' => $accidents]);
    }

    public function store(Request $request, string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $validated = $request->validate([
            'accident_date'     => ['required', 'date'],
            'accident_time'     => ['nullable', 'date_format:H:i'],
            'accident_type'     => ['nullable', 'string', 'in:' . implode(',', self::ACCIDENT_TYPES)],
            'severity'          => ['nullable', 'string', 'in:' . implode(',', self::SEVERITIES)],
            'accident_location' => ['nullable', 'string', 'max:300'],
            'description'       => ['required', 'string', 'max:3000'],
            'body_part_affected'=> ['nullable', 'string', 'max:300'],
            'injury_type'       => ['nullable', 'string', 'max:200'],
            'immediate_cause'   => ['nullable', 'string', 'max:500'],
            'root_cause'        => ['nullable', 'string', 'max:500'],
            'lost_days'         => ['nullable', 'integer', 'min:0'],
            'iess_reported'     => ['nullable', 'boolean'],
            'at01_number'       => ['nullable', 'string', 'max:100'],
            'iess_report_date'  => ['nullable', 'date'],
            'corrective_actions'=> ['nullable', 'string', 'max:2000'],
            'preventive_actions'=> ['nullable', 'string', 'max:2000'],
            'evaluation_id'     => ['nullable', 'uuid', 'exists:occupational_evaluations,id'],
            'status'            => ['nullable', 'string', 'in:' . implode(',', self::STATUSES)],
        ]);

        $validated['worker_id']           = $workerId;
        $validated['reported_by_user_id'] = $request->user()->id;
        $validated['accident_type']       = $validated['accident_type'] ?? 'ACCIDENT';
        $validated['severity']            = $validated['severity'] ?? 'MINOR';
        $validated['status']              = $validated['status'] ?? 'OPEN';

        $accident = OccupationalAccident::create($validated);
        $accident->load('reportedBy:id,name');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'CREATE_ACCIDENT_REPORT',
            'entity_type' => 'OccupationalAccident',
            'entity_id'   => (string) $accident->id,
            'description' => "Accidente {$accident->accident_type} registrado — {$accident->severity} — trabajador {$workerId}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapAccident($accident)], 201);
    }

    public function update(Request $request, string $workerId, int $accidentId): JsonResponse
    {
        $accident = OccupationalAccident::where('worker_id', $workerId)->findOrFail($accidentId);

        $validated = $request->validate([
            'accident_date'     => ['nullable', 'date'],
            'accident_time'     => ['nullable', 'date_format:H:i'],
            'accident_type'     => ['nullable', 'string', 'in:' . implode(',', self::ACCIDENT_TYPES)],
            'severity'          => ['nullable', 'string', 'in:' . implode(',', self::SEVERITIES)],
            'accident_location' => ['nullable', 'string', 'max:300'],
            'description'       => ['nullable', 'string', 'max:3000'],
            'body_part_affected'=> ['nullable', 'string', 'max:300'],
            'injury_type'       => ['nullable', 'string', 'max:200'],
            'immediate_cause'   => ['nullable', 'string', 'max:500'],
            'root_cause'        => ['nullable', 'string', 'max:500'],
            'lost_days'         => ['nullable', 'integer', 'min:0'],
            'iess_reported'     => ['nullable', 'boolean'],
            'at01_number'       => ['nullable', 'string', 'max:100'],
            'iess_report_date'  => ['nullable', 'date'],
            'corrective_actions'=> ['nullable', 'string', 'max:2000'],
            'preventive_actions'=> ['nullable', 'string', 'max:2000'],
            'status'            => ['nullable', 'string', 'in:' . implode(',', self::STATUSES)],
        ]);

        $accident->update($validated);
        $accident->load('reportedBy:id,name');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'UPDATE_ACCIDENT_REPORT',
            'entity_type' => 'OccupationalAccident',
            'entity_id'   => (string) $accident->id,
            'description' => "Accidente {$accident->id} actualizado — estado: {$accident->status}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapAccident($accident)]);
    }

    public function destroy(Request $request, string $workerId, int $accidentId): JsonResponse
    {
        $accident = OccupationalAccident::where('worker_id', $workerId)->findOrFail($accidentId);
        $accident->delete();

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'DELETE_ACCIDENT_REPORT',
            'entity_type' => 'OccupationalAccident',
            'entity_id'   => (string) $accidentId,
            'description' => "Accidente {$accidentId} eliminado",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }

    public function generatePdf(Request $request, string $workerId, int $accidentId): Response
    {
        $accident = OccupationalAccident::where('worker_id', $workerId)
            ->with(['worker.company', 'worker.jobPosition', 'reportedBy'])
            ->findOrFail($accidentId);

        $worker    = $accident->worker;
        $birthDate = $worker->birth_date ? \Carbon\Carbon::parse($worker->birth_date) : null;

        $typeLabels = [
            'ACCIDENT'            => 'Accidente de trabajo',
            'NEAR_MISS'           => 'Casi-accidente / Incidente',
            'OCCUPATIONAL_DISEASE'=> 'Enfermedad ocupacional',
            'COMMUTING'           => 'Accidente in itinere (trayecto)',
        ];
        $severityLabels = [
            'MINOR'    => 'Leve',
            'MODERATE' => 'Moderado',
            'SERIOUS'  => 'Grave',
            'FATAL'    => 'Fatal',
        ];
        $statusLabels = ['OPEN' => 'Abierto', 'INVESTIGATING' => 'En investigación', 'CLOSED' => 'Cerrado'];

        $pdf = Pdf::loadView('pdf.accident-report', [
            'accident'     => $accident,
            'typeLabel'    => $typeLabels[$accident->accident_type] ?? $accident->accident_type,
            'severityLabel'=> $severityLabels[$accident->severity] ?? $accident->severity,
            'statusLabel'  => $statusLabels[$accident->status] ?? $accident->status,
            'worker'       => [
                'full_name'       => trim(($worker->first_name ?? '') . ' ' . ($worker->last_name ?? '')),
                'document_number' => $worker->document_number ?? '-',
                'age'             => $birthDate ? $birthDate->age . ' años' : '-',
                'sex'             => $worker->sex ?? '-',
                'company'         => $worker->company?->name ?? ($worker->company?->business_name ?? '-'),
                'job_position'    => $worker->jobPosition?->name ?? '-',
            ],
            'doctor'  => [
                'name' => $accident->reportedBy?->name ?? 'MÉDICO OCUPACIONAL',
                'code' => $accident->reportedBy?->professional_code ?? '',
            ],
            'config'  => config('shcso', []),
            'fecha'   => now()->format('d/m/Y'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'GENERATE_ACCIDENT_PDF',
            'entity_type' => 'OccupationalAccident',
            'entity_id'   => (string) $accident->id,
            'description' => "PDF AT-01 generado — accidente {$accident->id}",
            'ip_address'  => $request->ip(),
        ]);

        return $pdf->stream("reporte-accidente-{$accident->id}.pdf");
    }

    private function mapAccident(OccupationalAccident $a): array
    {
        return [
            'id'                 => $a->id,
            'worker_id'          => $a->worker_id,
            'evaluation_id'      => $a->evaluation_id,
            'accident_date'      => $a->accident_date?->toDateString(),
            'accident_time'      => $a->accident_time,
            'accident_type'      => $a->accident_type,
            'severity'           => $a->severity,
            'accident_location'  => $a->accident_location,
            'description'        => $a->description,
            'body_part_affected' => $a->body_part_affected,
            'injury_type'        => $a->injury_type,
            'immediate_cause'    => $a->immediate_cause,
            'root_cause'         => $a->root_cause,
            'lost_days'          => $a->lost_days,
            'iess_reported'      => $a->iess_reported,
            'at01_number'        => $a->at01_number,
            'iess_report_date'   => $a->iess_report_date?->toDateString(),
            'corrective_actions' => $a->corrective_actions,
            'preventive_actions' => $a->preventive_actions,
            'status'             => $a->status,
            'reported_by'        => $a->reportedBy ? ['id' => $a->reportedBy->id, 'name' => $a->reportedBy->name] : null,
            'created_at'         => $a->created_at?->toIso8601String(),
            'updated_at'         => $a->updated_at?->toIso8601String(),
        ];
    }
}
