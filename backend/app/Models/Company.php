<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'ruc',
        'ciiu',
        'business_name',
        'work_center',
        'address',
    ];

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}
