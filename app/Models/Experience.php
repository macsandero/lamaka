<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'title',
        'description',
        'experience_type',
        'purpose',
        'experience_details',
        'short_duration',
        'short_price',
        'long_duration',
        'long_price',
        'third_duration',
        'third_price',
        'fourth_duration',
        'fourth_price',
        'ideal_for',
        'image',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * @return array<int, string>
     */
    public function detailsList(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->experience_details))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
