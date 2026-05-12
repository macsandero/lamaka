<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSubmission extends Model
{
    public const STATUSES = [
        'new' => 'Nuova',
        'contacted' => 'Contattata',
        'confirmed' => 'Confermata',
        'closed' => 'Chiusa',
    ];

    protected $fillable = [
        'reference',
        'data',
        'status',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function flatData(): array
    {
        return collect($this->data ?? [])
            ->mapWithKeys(fn (array $field): array => [
                $field['label'] ?? 'Campo' => is_bool($field['value'] ?? null)
                    ? (($field['value'] ?? false) ? 'Si' : 'No')
                    : ($field['value'] ?? ''),
            ])
            ->all();
    }

    public function fieldValue(string $key): ?string
    {
        $value = data_get($this->data, "{$key}.value");

        return is_scalar($value) ? (string) $value : null;
    }
}
