<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalExamOrder extends Model
{
    protected $fillable = [
        'worker_id',
        'evaluation_id',
        'ordered_by_user_id',
        'order_type',
        'priority',
        'order_date',
        'clinical_indication',
        'studies',
        'additional_notes',
        'status',
    ];

    protected $casts = [
        'studies'    => 'array',
        'order_date' => 'date',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(OccupationalEvaluation::class, 'evaluation_id');
    }

    public function orderedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by_user_id');
    }
}
