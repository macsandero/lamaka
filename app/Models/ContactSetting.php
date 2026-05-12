<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'business_name',
        'heading',
        'body',
        'footer_body',
        'email',
        'phone',
        'whatsapp',
        'address',
        'map_query',
        'instagram_url',
        'facebook_url',
        'footer_note',
        'directions_label',
        'directions_url',
        'privacy_url',
        'cookie_url',
        'terms_url',
        'legal_text',
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
