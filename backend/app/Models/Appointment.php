<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasUuids;

    protected $fillable = [
        'worker_id', 'patient_name', 'doctor_id', 'appointment_date', 'appointment_time',
        'type', 'status', 'reason', 'notes', 'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public const TYPES = [
        'CONSULTA'               => 'Consulta general',
        'EXAMEN_PREOCUPACIONAL'  => 'Examen preocupacional',
        'EXAMEN_PERIODICO'       => 'Examen periódico',
        'EXAMEN_RETIRO'          => 'Examen de retiro',
        'SEGUIMIENTO'            => 'Seguimiento',
        'INTERCONSULTA'          => 'Interconsulta',
        'VACUNACION'             => 'Vacunación',
    ];

    public const STATUSES = [
        'PENDIENTE'           => 'Pendiente',
        'CONFIRMADA'          => 'Confirmada',
        'CANCELADA'           => 'Cancelada',
        'CANCELADA_PACIENTE'  => 'Cancelada por paciente',
        'NO_ASISTIO'          => 'No asistió',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
