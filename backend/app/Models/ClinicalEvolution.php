<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalEvolution extends Model
{
    protected $fillable = [
        'worker_id',
        'evaluation_id',
        'author_user_id',
        'evolution_type',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'vital_signs',
        'notes',
    ];

    protected $casts = [
        'vital_signs' => 'array',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(OccupationalEvaluation::class, 'evaluation_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }
}
