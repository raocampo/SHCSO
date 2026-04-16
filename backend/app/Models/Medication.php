<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'code',
        'generic_name',
        'commercial_name',
        'concentration',
        'pharmaceutical_form',
        'therapeutic_group',
        'via_administracion',
        'controlled',
        'active',
    ];

    protected $casts = [
        'controlled' => 'boolean',
        'active'     => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
