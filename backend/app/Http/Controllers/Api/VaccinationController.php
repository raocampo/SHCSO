<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Worker;
use App\Models\WorkerVaccination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    private const COMMON_VACCINES = [
        'COVID-19 (Pfizer-BioNTech)',
        'COVID-19 (Moderna)',
        'COVID-19 (AstraZeneca)',
        'Influenza',
        'Hepatitis B',
        'Hepatitis A',
        'Tétanos-Difteria (Td)',
        'Triple Viral (SRP)',
        'Fiebre Amarilla',
        'Varicela',
        'Neumococo (PCV13)',
        'Meningococo',
        'HPV (Papilomavirus)',
        'Tifoidea',
        'Rabia',
    ];

    public function index(string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $vaccinations = WorkerVaccination::where('worker_id', $workerId)
            ->with('appliedBy:id,name')
            ->orderBy('applied_date', 'desc')
            ->get()
            ->map(fn ($v) => $this->mapVaccination($v));

        return response()->json(['ok' => true, 'data' => $vaccinations]);
    }

    public function store(Request $request, string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $validated = $request->validate([
            'vaccine_name'      => ['required', 'string', 'max:200'],
            'commercial_name'   => ['nullable', 'string', 'max:200'],
            'lot_number'        => ['nullable', 'string', 'max:100'],
            'dose_number'       => ['nullable', 'string', 'max:20'],
            'route'             => ['nullable', 'string', 'max:50'],
            'applied_date'      => ['required', 'date'],
            'next_dose_date'    => ['nullable', 'date', 'after:applied_date'],
            'administered_by'   => ['nullable', 'string', 'max:200'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['worker_id']          = $workerId;
        $validated['applied_by_user_id'] = $request->user()->id;

        $vaccination = WorkerVaccination::create($validated);
        $vaccination->load('appliedBy:id,name');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'CREATE_VACCINATION',
            'entity_type' => 'WorkerVaccination',
            'entity_id'   => (string) $vaccination->id,
            'description' => "Vacuna {$vaccination->vaccine_name} registrada para trabajador {$workerId}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapVaccination($vaccination)], 201);
    }

    public function update(Request $request, string $workerId, int $vaccinationId): JsonResponse
    {
        $vaccination = WorkerVaccination::where('worker_id', $workerId)->findOrFail($vaccinationId);

        $validated = $request->validate([
            'vaccine_name'    => ['nullable', 'string', 'max:200'],
            'commercial_name' => ['nullable', 'string', 'max:200'],
            'lot_number'      => ['nullable', 'string', 'max:100'],
            'dose_number'     => ['nullable', 'string', 'max:20'],
            'route'           => ['nullable', 'string', 'max:50'],
            'applied_date'    => ['nullable', 'date'],
            'next_dose_date'  => ['nullable', 'date'],
            'administered_by' => ['nullable', 'string', 'max:200'],
            'notes'           => ['nullable', 'string', 'max:2000'],
        ]);

        $vaccination->update($validated);
        $vaccination->load('appliedBy:id,name');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'UPDATE_VACCINATION',
            'entity_type' => 'WorkerVaccination',
            'entity_id'   => (string) $vaccination->id,
            'description' => "Vacuna {$vaccination->id} actualizada",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapVaccination($vaccination)]);
    }

    public function destroy(Request $request, string $workerId, int $vaccinationId): JsonResponse
    {
        $vaccination = WorkerVaccination::where('worker_id', $workerId)->findOrFail($vaccinationId);
        $vaccination->delete();

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'DELETE_VACCINATION',
            'entity_type' => 'WorkerVaccination',
            'entity_id'   => (string) $vaccinationId,
            'description' => "Vacuna {$vaccinationId} eliminada",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }

    private function mapVaccination(WorkerVaccination $v): array
    {
        return [
            'id'              => $v->id,
            'worker_id'       => $v->worker_id,
            'vaccine_name'    => $v->vaccine_name,
            'commercial_name' => $v->commercial_name,
            'lot_number'      => $v->lot_number,
            'dose_number'     => $v->dose_number,
            'route'           => $v->route,
            'applied_date'    => $v->applied_date?->toDateString(),
            'next_dose_date'  => $v->next_dose_date?->toDateString(),
            'administered_by' => $v->administered_by,
            'notes'           => $v->notes,
            'applied_by'      => $v->appliedBy ? ['id' => $v->appliedBy->id, 'name' => $v->appliedBy->name] : null,
            'created_at'      => $v->created_at?->toIso8601String(),
        ];
    }
}
