<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BookingFormField extends Model
{
    public const TYPES = [
        'text' => 'Testo',
        'email' => 'Email',
        'tel' => 'Telefono',
        'date' => 'Data',
        'datetime' => 'Data e ora',
        'number' => 'Numero',
        'select' => 'Selezione',
        'textarea' => 'Testo lungo',
        'checkbox' => 'Checkbox',
    ];

    protected $fillable = [
        'label',
        'key',
        'type',
        'placeholder',
        'help_text',
        'options',
        'is_required',
        'is_active',
        'is_full_width',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::saving(function (BookingFormField $field): void {
            if (blank($field->key)) {
                $field->key = Str::slug($field->label, '_');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'is_full_width' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }

    /**
     * @return array<int, string>
     */
    public function optionsList(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->options))
            ->map(fn (string $option): string => trim($option))
            ->filter()
            ->values()
            ->all();
    }
}
