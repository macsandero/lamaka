<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'body',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
