<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class EvaluationDiagnosis extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'evaluation_id',
        'diagnosis_code',
        'diagnosis_type',
        'notes',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(OccupationalEvaluation::class, 'evaluation_id');
    }

    public function diagnosisCatalog(): BelongsTo
    {
        return $this->belongsTo(DiagnosisCatalog::class, 'diagnosis_code', 'code');
    }
}
