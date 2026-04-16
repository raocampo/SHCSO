<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerVaccination extends Model
{
    protected $fillable = [
        'worker_id',
        'applied_by_user_id',
        'vaccine_name',
        'commercial_name',
        'lot_number',
        'dose_number',
        'route',
        'applied_date',
        'next_dose_date',
        'administered_by',
        'notes',
    ];

    protected $casts = [
        'applied_date'   => 'date',
        'next_dose_date' => 'date',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by_user_id');
    }
}
