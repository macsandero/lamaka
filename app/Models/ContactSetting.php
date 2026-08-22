<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasLocalizedContent;

    protected $fillable = [
        'business_name',
        'footer_logo',
        'heading',
        'body',
        'footer_body',
        'email',
        'phone',
        'whatsapp',
        'address',
        'map_query',
        'instagram_url',
        'instagram_note',
        'facebook_url',
        'footer_note',
        'directions_label',
        'directions_url',
        'privacy_url',
        'cookie_url',
        'terms_url',
        'legal_text',
        'company_name',
        'tax_code',
        'vat_number',
        'booking_label',
        'booking_url',
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
