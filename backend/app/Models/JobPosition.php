<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = [
        'ciuo_code',
        'name',
        'description',
    ];

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}
