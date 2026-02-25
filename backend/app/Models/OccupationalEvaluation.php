<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class OccupationalEvaluation extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'worker_id',
        'evaluator_user_id',
        'evaluation_type',
        'attention_date',
        'consultation_reason',
        'personal_background',
        'current_problem',
        'vital_signs',
        'physical_exam',
        'risk_factors',
        'labor_activity_history',
        'extra_activities',
        'exam_results',
        'medical_aptitude',
        'restrictions',
        'recommendations',
        'retirement_notes',
        'professional_name',
        'professional_code',
        'worker_signature_path',
    ];

    protected function casts(): array
    {
        return [
            'attention_date' => 'date',
            'personal_background' => 'array',
            'vital_signs' => 'array',
            'physical_exam' => 'array',
            'risk_factors' => 'array',
            'labor_activity_history' => 'array',
            'extra_activities' => 'array',
            'exam_results' => 'array',
        ];
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(EvaluationDiagnosis::class, 'evaluation_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EvaluationAttachment::class, 'evaluation_id');
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(EvaluationPrescription::class, 'evaluation_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(MedicalCertificate::class, 'evaluation_id');
    }
}
