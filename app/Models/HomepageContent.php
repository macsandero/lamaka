<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    use HasLocalizedContent;

    protected $fillable = [
        'hero_eyebrow',
        'hero_title',
        'hero_subtitle',
        'hero_button_label',
        'hero_button_anchor',
        'hero_video',
        'experiences_eyebrow',
        'experiences_title',
        'about_eyebrow',
        'about_title',
        'about_body',
        'about_image',
        'animals_eyebrow',
        'animals_title',
        'translations',
    ];

    protected function casts(): array
    {
        return $this->localizedContentCasts();
    }
}
