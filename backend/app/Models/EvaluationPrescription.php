<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationPrescription extends Model
{
    protected $fillable = [
        'evaluation_id',
        'medication',
        'dosage',
        'frequency',
        'duration',
        'indications',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(OccupationalEvaluation::class, 'evaluation_id');
    }
}

