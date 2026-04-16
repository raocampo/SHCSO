<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ClinicalEvolution;
use App\Models\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClinicalEvolutionController extends Controller
{
    public function index(Request $request, string $workerId): JsonResponse
    {
        $worker = Worker::findOrFail($workerId);

        $evolutions = ClinicalEvolution::where('worker_id', $workerId)
            ->with('author:id,name')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($e) => $this->mapEvolution($e));

        return response()->json(['ok' => true, 'data' => $evolutions]);
    }

    public function store(Request $request, string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $validated = $request->validate([
            'evolution_type'  => ['nullable', 'string', 'in:SEGUIMIENTO,NOTA,INTERCONSULTA,URGENCIA'],
            'evaluation_id'   => ['nullable', 'uuid', 'exists:occupational_evaluations,id'],
            'subjective'      => ['nullable', 'string', 'max:3000'],
            'objective'       => ['nullable', 'string', 'max:3000'],
            'assessment'      => ['nullable', 'string', 'max:3000'],
            'plan'            => ['nullable', 'string', 'max:3000'],
            'notes'           => ['nullable', 'string', 'max:2000'],
            'vital_signs'          => ['nullable', 'array'],
            'vital_signs.bp'       => ['nullable', 'string', 'max:20'],
            'vital_signs.temp'     => ['nullable', 'numeric'],
            'vital_signs.hr'       => ['nullable', 'integer'],
            'vital_signs.rr'       => ['nullable', 'integer'],
            'vital_signs.weight'   => ['nullable', 'numeric'],
            'vital_signs.height'   => ['nullable', 'numeric'],
            'vital_signs.spo2'     => ['nullable', 'numeric'],
            'vital_signs.glucose'  => ['nullable', 'numeric'],
        ]);

        $validated['worker_id']      = $workerId;
        $validated['author_user_id'] = $request->user()->id;
        $validated['evolution_type'] = $validated['evolution_type'] ?? 'SEGUIMIENTO';

        $evolution = ClinicalEvolution::create($validated);
        $evolution->load('author:id,name');

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'CREATE_EVOLUTION',
            'entity_type'=> 'ClinicalEvolution',
            'entity_id'  => (string) $evolution->id,
            'description'=> "Evolución {$evolution->evolution_type} registrada para trabajador {$workerId}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapEvolution($evolution)], 201);
    }

    public function show(Request $request, string $workerId, int $evolutionId): JsonResponse
    {
        $evolution = ClinicalEvolution::where('worker_id', $workerId)
            ->with('author:id,name')
            ->findOrFail($evolutionId);

        return response()->json(['ok' => true, 'data' => $this->mapEvolution($evolution)]);
    }

    public function update(Request $request, string $workerId, int $evolutionId): JsonResponse
    {
        $evolution = ClinicalEvolution::where('worker_id', $workerId)->findOrFail($evolutionId);

        $validated = $request->validate([
            'evolution_type'  => ['nullable', 'string', 'in:SEGUIMIENTO,NOTA,INTERCONSULTA,URGENCIA'],
            'subjective'      => ['nullable', 'string', 'max:3000'],
            'objective'       => ['nullable', 'string', 'max:3000'],
            'assessment'      => ['nullable', 'string', 'max:3000'],
            'plan'            => ['nullable', 'string', 'max:3000'],
            'notes'           => ['nullable', 'string', 'max:2000'],
            'vital_signs'          => ['nullable', 'array'],
            'vital_signs.bp'       => ['nullable', 'string', 'max:20'],
            'vital_signs.temp'     => ['nullable', 'numeric'],
            'vital_signs.hr'       => ['nullable', 'integer'],
            'vital_signs.rr'       => ['nullable', 'integer'],
            'vital_signs.weight'   => ['nullable', 'numeric'],
            'vital_signs.height'   => ['nullable', 'numeric'],
            'vital_signs.spo2'     => ['nullable', 'numeric'],
            'vital_signs.glucose'  => ['nullable', 'numeric'],
        ]);

        $evolution->update($validated);
        $evolution->load('author:id,name');

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'UPDATE_EVOLUTION',
            'entity_type'=> 'ClinicalEvolution',
            'entity_id'  => (string) $evolution->id,
            'description'=> "Evolución {$evolution->id} actualizada",
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapEvolution($evolution)]);
    }

    public function destroy(Request $request, string $workerId, int $evolutionId): JsonResponse
    {
        $evolution = ClinicalEvolution::where('worker_id', $workerId)->findOrFail($evolutionId);
        $evolution->delete();

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'DELETE_EVOLUTION',
            'entity_type'=> 'ClinicalEvolution',
            'entity_id'  => (string) $evolutionId,
            'description'=> "Evolución {$evolutionId} eliminada",
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }

    private function mapEvolution(ClinicalEvolution $e): array
    {
        return [
            'id'             => $e->id,
            'worker_id'      => $e->worker_id,
            'evaluation_id'  => $e->evaluation_id,
            'evolution_type' => $e->evolution_type,
            'subjective'     => $e->subjective,
            'objective'      => $e->objective,
            'assessment'     => $e->assessment,
            'plan'           => $e->plan,
            'vital_signs'    => $e->vital_signs ?? [],
            'notes'          => $e->notes,
            'author'         => $e->author ? ['id' => $e->author->id, 'name' => $e->author->name] : null,
            'created_at'     => $e->created_at?->toIso8601String(),
            'updated_at'     => $e->updated_at?->toIso8601String(),
        ];
    }
}
