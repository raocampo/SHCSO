<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = [
        'ciuo_code',
        'ciiu_code',
        'ciiu_level',
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'ciiu_level' => 'integer',
        ];
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}
