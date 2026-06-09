<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = [
        'ciuo_code',
        'ciuo_level',
        'ciiu_code',
        'ciiu_level',
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'ciuo_level' => 'integer',
            'ciiu_level' => 'integer',
        ];
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}
