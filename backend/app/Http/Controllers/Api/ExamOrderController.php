<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MedicalExamOrder;
use App\Models\Worker;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamOrderController extends Controller
{
    private const ORDER_TYPES   = ['LAB', 'IMAGING', 'PATHOLOGY', 'FUNCTIONAL'];
    private const PRIORITIES    = ['URGENT', 'NORMAL', 'ROUTINE'];
    private const STATUSES      = ['PENDING', 'COMPLETED', 'PARTIAL', 'CANCELLED'];

    public function index(string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $orders = MedicalExamOrder::where('worker_id', $workerId)
            ->with('orderedBy:id,name')
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn ($o) => $this->mapOrder($o));

        return response()->json(['ok' => true, 'data' => $orders]);
    }

    public function store(Request $request, string $workerId): JsonResponse
    {
        Worker::findOrFail($workerId);

        $validated = $request->validate([
            'order_type'         => ['required', 'string', 'in:' . implode(',', self::ORDER_TYPES)],
            'priority'           => ['nullable', 'string', 'in:' . implode(',', self::PRIORITIES)],
            'order_date'         => ['required', 'date'],
            'evaluation_id'      => ['nullable', 'uuid', 'exists:occupational_evaluations,id'],
            'clinical_indication'=> ['nullable', 'string', 'max:1000'],
            'studies'            => ['required', 'array', 'min:1'],
            'studies.*.name'     => ['required', 'string', 'max:300'],
            'studies.*.notes'    => ['nullable', 'string', 'max:500'],
            'additional_notes'   => ['nullable', 'string', 'max:2000'],
            'status'             => ['nullable', 'string', 'in:' . implode(',', self::STATUSES)],
        ]);

        $validated['worker_id']          = $workerId;
        $validated['ordered_by_user_id'] = $request->user()->id;
        $validated['priority']           = $validated['priority'] ?? 'NORMAL';
        $validated['status']             = $validated['status'] ?? 'PENDING';

        $order = MedicalExamOrder::create($validated);
        $order->load('orderedBy:id,name');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'CREATE_EXAM_ORDER',
            'entity_type' => 'MedicalExamOrder',
            'entity_id'   => (string) $order->id,
            'description' => "Pedido {$order->order_type} creado para trabajador {$workerId}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapOrder($order)], 201);
    }

    public function show(string $workerId, int $orderId): JsonResponse
    {
        $order = MedicalExamOrder::where('worker_id', $workerId)
            ->with('orderedBy:id,name')
            ->findOrFail($orderId);

        return response()->json(['ok' => true, 'data' => $this->mapOrder($order)]);
    }

    public function update(Request $request, string $workerId, int $orderId): JsonResponse
    {
        $order = MedicalExamOrder::where('worker_id', $workerId)->findOrFail($orderId);

        $validated = $request->validate([
            'order_type'         => ['nullable', 'string', 'in:' . implode(',', self::ORDER_TYPES)],
            'priority'           => ['nullable', 'string', 'in:' . implode(',', self::PRIORITIES)],
            'order_date'         => ['nullable', 'date'],
            'clinical_indication'=> ['nullable', 'string', 'max:1000'],
            'studies'            => ['nullable', 'array', 'min:1'],
            'studies.*.name'     => ['required_with:studies', 'string', 'max:300'],
            'studies.*.notes'    => ['nullable', 'string', 'max:500'],
            'additional_notes'   => ['nullable', 'string', 'max:2000'],
            'status'             => ['nullable', 'string', 'in:' . implode(',', self::STATUSES)],
        ]);

        $order->update($validated);
        $order->load('orderedBy:id,name');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'UPDATE_EXAM_ORDER',
            'entity_type' => 'MedicalExamOrder',
            'entity_id'   => (string) $order->id,
            'description' => "Pedido {$order->id} actualizado — estado: {$order->status}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'data' => $this->mapOrder($order)]);
    }

    public function destroy(Request $request, string $workerId, int $orderId): JsonResponse
    {
        $order = MedicalExamOrder::where('worker_id', $workerId)->findOrFail($orderId);
        $order->delete();

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'DELETE_EXAM_ORDER',
            'entity_type' => 'MedicalExamOrder',
            'entity_id'   => (string) $orderId,
            'description' => "Pedido {$orderId} eliminado",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }

    public function generatePdf(Request $request, string $workerId, int $orderId): Response
    {
        $order = MedicalExamOrder::where('worker_id', $workerId)
            ->with(['worker.company', 'orderedBy'])
            ->findOrFail($orderId);

        $worker = $order->worker;
        $birthDate = $worker->birth_date ? \Carbon\Carbon::parse($worker->birth_date) : null;

        $orderTypeTitles = [
            'LAB'       => 'Laboratorio Clínico',
            'IMAGING'   => 'Imágenes Diagnósticas',
            'PATHOLOGY' => 'Anatomía Patológica',
            'FUNCTIONAL'=> 'Pruebas Funcionales',
        ];
        $priorityLabels = ['URGENT' => 'URGENTE', 'NORMAL' => 'NORMAL', 'ROUTINE' => 'RUTINA'];

        $pdf = Pdf::loadView('pdf.exam-order', [
            'order'      => $order,
            'orderTitle' => $orderTypeTitles[$order->order_type] ?? $order->order_type,
            'priority'   => $priorityLabels[$order->priority] ?? $order->priority,
            'worker'     => [
                'full_name'       => trim(($worker->first_name ?? '') . ' ' . ($worker->last_name ?? '')),
                'document_number' => $worker->document_number ?? '-',
                'age'             => $birthDate ? $birthDate->age . ' años' : '-',
                'sex'             => $worker->sex_label,
                'company'         => $worker->company?->name ?? '-',
                'job_position'    => $worker->jobPosition
                    ? trim((($worker->jobPosition->ciuo_code ?? $worker->jobPosition->ciiu_code ?? null) ? ($worker->jobPosition->ciuo_code ?? $worker->jobPosition->ciiu_code) . ' - ' : '') . $worker->jobPosition->name)
                    : '-',
            ],
            'doctor'     => [
                'name'  => $order->orderedBy?->name ?? 'MÉDICO OCUPACIONAL',
                'code'  => $order->orderedBy?->professional_code ?? '',
            ],
            'config'     => config('shcso', []),
            'fecha'      => now()->format('d/m/Y'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'GENERATE_EXAM_ORDER_PDF',
            'entity_type' => 'MedicalExamOrder',
            'entity_id'   => (string) $order->id,
            'description' => "PDF pedido {$order->order_type} generado — trabajador {$workerId}",
            'ip_address'  => $request->ip(),
        ]);

        return $pdf->stream("pedido-{$order->order_type}-{$order->id}.pdf");
    }

    private function mapOrder(MedicalExamOrder $o): array
    {
        return [
            'id'                  => $o->id,
            'worker_id'           => $o->worker_id,
            'evaluation_id'       => $o->evaluation_id,
            'order_type'          => $o->order_type,
            'priority'            => $o->priority,
            'order_date'          => $o->order_date?->toDateString(),
            'clinical_indication' => $o->clinical_indication,
            'studies'             => $o->studies ?? [],
            'additional_notes'    => $o->additional_notes,
            'status'              => $o->status,
            'ordered_by'          => $o->orderedBy ? ['id' => $o->orderedBy->id, 'name' => $o->orderedBy->name] : null,
            'created_at'          => $o->created_at?->toIso8601String(),
            'updated_at'          => $o->updated_at?->toIso8601String(),
        ];
    }
}
