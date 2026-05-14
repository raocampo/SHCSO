<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Appointment::with(['worker', 'doctor'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');

        if ($request->filled('date_from')) {
            $q->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->whereDate('appointment_date', '<=', $request->date_to);
        }
        if ($request->filled('date')) {
            $q->whereDate('appointment_date', $request->date);
        }
        if ($request->filled('worker_id')) {
            $q->where('worker_id', $request->worker_id);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        $perPage = min((int) ($request->per_page ?? 20), 100);
        $paginated = $q->paginate($perPage);

        return response()->json([
            'ok'   => true,
            'data' => $paginated->items() ? array_map([$this, 'map'], $paginated->items()) : [],
            'meta' => [
                'total'       => $paginated->total(),
                'page'        => $paginated->currentPage(),
                'per_page'    => $paginated->perPage(),
                'total_pages' => $paginated->lastPage(),
                'has_next'    => $paginated->hasMorePages(),
                'has_prev'    => $paginated->currentPage() > 1,
            ],
        ]);
    }

    public function today(): JsonResponse
    {
        $items = Appointment::with(['worker', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        return response()->json([
            'ok'   => true,
            'data' => $items->map(fn($a) => $this->map($a))->values(),
        ]);
    }

    public function upcoming(): JsonResponse
    {
        $items = Appointment::with(['worker', 'doctor'])
            ->whereDate('appointment_date', '>=', today())
            ->whereIn('status', ['PENDIENTE', 'CONFIRMADA'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        return response()->json([
            'ok'   => true,
            'data' => $items->map(fn($a) => $this->map($a))->values(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'worker_id'        => ['nullable', 'uuid', 'exists:workers,id'],
            'patient_name'     => ['nullable', 'string', 'max:200'],
            'doctor_id'        => ['nullable', 'uuid', 'exists:users,id'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'type'             => ['required', 'string', 'in:' . implode(',', array_keys(Appointment::TYPES))],
            'status'           => ['sometimes', 'string', 'in:' . implode(',', array_keys(Appointment::STATUSES))],
            'reason'           => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        if (empty($data['worker_id']) && empty($data['patient_name'])) {
            return response()->json(['ok' => false, 'message' => 'Indica un trabajador o un nombre de paciente.'], 422);
        }

        $data['created_by'] = $request->user()->id;
        $data['status']     = $data['status'] ?? 'PENDIENTE';

        $appointment = Appointment::create($data);
        $appointment->load(['worker', 'doctor']);

        $patientLabel = $appointment->worker
            ? trim($appointment->worker->first_name . ' ' . $appointment->worker->last_name)
            : ($appointment->patient_name ?? 'externo');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'CREATE_APPOINTMENT',
            'entity_type' => 'Appointment',
            'entity_id'   => $appointment->id,
            'description' => "Cita programada para {$patientLabel} el {$appointment->appointment_date->format('d/m/Y')} {$appointment->appointment_time}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->map($appointment)], 201);
    }

    public function show(string $id): JsonResponse
    {
        $a = Appointment::with(['worker', 'doctor'])->findOrFail($id);
        return response()->json(['ok' => true, 'data' => $this->map($a)]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);

        $data = $request->validate([
            'worker_id'        => ['nullable', 'uuid', 'exists:workers,id'],
            'patient_name'     => ['nullable', 'string', 'max:200'],
            'doctor_id'        => ['nullable', 'uuid', 'exists:users,id'],
            'appointment_date' => ['sometimes', 'date'],
            'appointment_time' => ['sometimes', 'date_format:H:i'],
            'type'             => ['sometimes', 'string', 'in:' . implode(',', array_keys(Appointment::TYPES))],
            'status'           => ['sometimes', 'string', 'in:' . implode(',', array_keys(Appointment::STATUSES))],
            'reason'           => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $appointment->update($data);
        $appointment->load(['worker', 'doctor']);

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'UPDATE_APPOINTMENT',
            'entity_type' => 'Appointment',
            'entity_id'   => $appointment->id,
            'description' => "Cita actualizada: {$appointment->status}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->map($appointment)]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'DELETE_APPOINTMENT',
            'entity_type' => 'Appointment',
            'entity_id'   => $id,
            'description' => "Cita eliminada",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }

    public function catalog(): JsonResponse
    {
        return response()->json([
            'ok'   => true,
            'data' => [
                'types'    => array_map(fn($k, $v) => ['value' => $k, 'label' => $v], array_keys(Appointment::TYPES), Appointment::TYPES),
                'statuses' => array_map(fn($k, $v) => ['value' => $k, 'label' => $v], array_keys(Appointment::STATUSES), Appointment::STATUSES),
            ],
        ]);
    }

    private function map(Appointment $a): array
    {
        return [
            'id'               => $a->id,
            'worker_id'        => $a->worker_id,
            'patient_name'     => $a->patient_name,
            'worker_name'      => $a->worker ? trim($a->worker->first_name . ' ' . $a->worker->last_name) : ($a->patient_name ?? '-'),
            'worker_document'  => $a->worker?->document_number ?? '-',
            'worker_company'   => $a->worker?->company?->business_name ?? '-',
            'doctor_id'        => $a->doctor_id,
            'doctor_name'      => $a->doctor?->name ?? '-',
            'appointment_date' => $a->appointment_date?->format('Y-m-d'),
            'appointment_date_display' => $a->appointment_date?->format('d/m/Y'),
            'appointment_time' => $a->appointment_time,
            'type'             => $a->type,
            'type_label'       => Appointment::TYPES[$a->type] ?? $a->type,
            'status'           => $a->status,
            'status_label'     => Appointment::STATUSES[$a->status] ?? $a->status,
            'reason'           => $a->reason,
            'notes'            => $a->notes,
            'created_at'       => $a->created_at?->format('Y-m-d H:i'),
        ];
    }
}
