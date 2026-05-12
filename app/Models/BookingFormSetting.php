<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingFormSetting extends Model
{
    protected $fillable = [
        'eyebrow',
        'heading',
        'body',
        'image',
        'submit_label',
        'success_message',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
