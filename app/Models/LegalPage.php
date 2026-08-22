<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    use HasLocalizedContent;

    protected $fillable = [
        'slug',
        'title',
        'body',
        'is_active',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            ...$this->localizedContentCasts(),
        ];
    }
}
