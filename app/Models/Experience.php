<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasLocalizedContent;

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
        'duration_notes',
        'ideal_for',
        'available_weekdays',
        'image',
        'sort_order',
        'is_active',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'available_weekdays' => 'array',
            ...$this->localizedContentCasts(),
        ];
    }

    public function isAvailableOn(int $isoWeekday): bool
    {
        return $this->available_weekdays === null
            || in_array($isoWeekday, array_map('intval', $this->available_weekdays), true);
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

    /**
     * @return array<int, string>
     */
    public function durationNotesList(): array
    {
        $notes = (string) $this->duration_notes;

        if (preg_match_all('/<li\b[^>]*>(.*?)<\/li>/is', $notes, $matches)) {
            return collect($matches[1])
                ->map(fn (string $item): string => trim(html_entity_decode(strip_tags($item), ENT_QUOTES | ENT_HTML5, 'UTF-8')))
                ->filter()
                ->values()
                ->all();
        }

        return collect(preg_split('/\r\n|\r|\n/', strip_tags($notes)))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
