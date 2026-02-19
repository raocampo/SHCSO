<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class DiagnosisCatalog extends Model
{
    protected $table = 'diagnosis_catalog';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'description',
    ];

    public function evaluationDiagnoses(): HasMany
    {
        return $this->hasMany(EvaluationDiagnosis::class, 'diagnosis_code', 'code');
    }
}
