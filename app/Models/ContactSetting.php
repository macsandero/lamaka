<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'business_name',
    'heading',
    'body',
    'email',
    'phone',
    'whatsapp',
    'address',
    'instagram_url',
    'booking_label',
    'booking_url',
    'is_active',
])]
class ContactSetting extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
