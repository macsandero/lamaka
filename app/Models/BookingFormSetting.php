<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class BookingFormSetting extends Model
{
    use HasLocalizedContent;

    protected $fillable = [
        'eyebrow',
        'heading',
        'body',
        'image',
        'submit_label',
        'success_message',
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
