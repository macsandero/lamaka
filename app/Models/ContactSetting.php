<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'business_name',
        'heading',
        'body',
        'email',
        'phone',
        'whatsapp',
        'address',
        'map_query',
        'instagram_url',
        'booking_label',
        'booking_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
