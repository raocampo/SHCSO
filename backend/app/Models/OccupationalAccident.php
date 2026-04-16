<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OccupationalAccident extends Model
{
    protected $fillable = [
        'worker_id',
        'evaluation_id',
        'reported_by_user_id',
        'accident_date',
        'accident_time',
        'accident_type',
        'severity',
        'accident_location',
        'description',
        'body_part_affected',
        'injury_type',
        'immediate_cause',
        'root_cause',
        'lost_days',
        'iess_reported',
        'at01_number',
        'iess_report_date',
        'corrective_actions',
        'preventive_actions',
        'status',
    ];

    protected $casts = [
        'accident_date'    => 'date',
        'iess_report_date' => 'date',
        'iess_reported'    => 'boolean',
        'lost_days'        => 'integer',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(OccupationalEvaluation::class, 'evaluation_id');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }
}
