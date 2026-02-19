<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerClinicalHistory extends Model
{
    protected $primaryKey = 'worker_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'worker_id',
        'personal_background',
        'family_background',
        'allergies',
        'current_medication',
        'pathological_history',
        'surgical_history',
        'occupational_history',
        'lifestyle_notes',
        'longitudinal_notes',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}

