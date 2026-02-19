<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class EvaluationAttachment extends Model
{
    protected $fillable = [
        'evaluation_id',
        'file_name',
        'file_path',
        'mime_type',
        'uploaded_by',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(OccupationalEvaluation::class, 'evaluation_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
