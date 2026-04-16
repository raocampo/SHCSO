<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'history_number',
        'file_number',
        'document_type',
        'document_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'sex',
        'blood_type',
        'laterality',
        'is_pregnant',
        'has_disability',
        'catastrophic_disease',
        'is_elderly',
        'company_id',
        'job_position_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_pregnant' => 'boolean',
            'has_disability' => 'boolean',
            'catastrophic_disease' => 'boolean',
            'is_elderly' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(OccupationalEvaluation::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(MedicalCertificate::class);
    }

    public function clinicalHistory(): HasOne
    {
        return $this->hasOne(WorkerClinicalHistory::class, 'worker_id');
    }

    public function evolutions(): HasMany
    {
        return $this->hasMany(ClinicalEvolution::class, 'worker_id')->orderByDesc('created_at');
    }
}
