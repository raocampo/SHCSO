<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'certificate_code',
        'evaluation_id',
        'worker_id',
        'issue_date',
        'medical_aptitude',
        'observations',
        'recommendations',
        'professional_name',
        'professional_code',
        'worker_signature_path',
        'pdf_path',
        'qr_code_data',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(OccupationalEvaluation::class, 'evaluation_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
