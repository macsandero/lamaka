<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSubmission extends Model
{
    public const MAX_DAILY_ANIMALS = 5;

    public const SOURCES = [
        'Sito web', 'Instagram', 'Facebook', 'Google', 'Passaparola', 'Volantino', 'Altro',
    ];

    public const CANCELLATION_REASONS = [
        'Ripensamento cliente', 'Condizioni meteo', 'Altro',
    ];

    public const STATUSES = [
        'new' => 'Nuova',
        'contacted' => 'Contattata',
        'confirmed' => 'Confermata',
        'closed' => 'Chiusa',
        'cancelled' => 'Annullata',
    ];

    protected $fillable = [
        'reference',
        'data',
        'status',
        'notes',
        'ip_address',
        'user_agent',
        'booking_date',
        'start_time',
        'end_time',
        'participants',
        'animals',
        'customer_name',
        'phone',
        'email',
        'source',
        'source_other',
        'origin',
        'confirmed_at',
        'cancellation_reason',
        'cancellation_reason_other',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'booking_date' => 'date',
            'participants' => 'integer',
            'animals' => 'integer',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public static function confirmedAnimalsForDate(string $date, ?int $exceptId = null): int
    {
        return (int) static::query()
            ->whereDate('booking_date', $date)
            ->whereNotNull('confirmed_at')
            ->when($exceptId, fn ($query) => $query->whereKeyNot($exceptId))
            ->sum('animals');
    }

    public static function unavailableDates(): array
    {
        return static::query()
            ->selectRaw('booking_date, SUM(animals) as animals_total')
            ->whereNotNull('booking_date')
            ->whereNotNull('confirmed_at')
            ->groupBy('booking_date')
            ->havingRaw('SUM(animals) >= ?', [static::MAX_DAILY_ANIMALS])
            ->pluck('booking_date')
            ->map(fn ($date) => substr((string) $date, 0, 10))
            ->values()->all();
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

    public function receivedAtFormatted(): string
    {
        return $this->created_at
            ? $this->created_at->copy()->timezone(config('app.display_timezone', 'Europe/Rome'))->format('d/m/Y H:i')
            : '';
    }
}
