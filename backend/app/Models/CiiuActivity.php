<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CiiuActivity extends Model
{
    protected $fillable = [
        'code',
        'description',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
        ];
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'ciiu', 'code');
    }
}
