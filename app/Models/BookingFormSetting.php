<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Illuminate\Database\Eloquent\Model;

class BookingFormSetting extends Model
{
    use HasLocalizedContent;

    protected $fillable = [
        'eyebrow',
        'heading',
        'body',
        'image',
        'submit_label',
        'success_message',
        'is_active',
        'bookings_suspended',
        'booking_suspension_until',
        'booking_suspension_message',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'bookings_suspended' => 'boolean',
            'booking_suspension_until' => 'date',
            ...$this->localizedContentCasts(),
        ];
    }

    public function bookingsAreSuspended(): bool
    {
        return $this->bookings_suspended
            && $this->booking_suspension_until !== null
            && now(config('app.display_timezone', 'Europe/Rome'))->toDateString()
                <= $this->booking_suspension_until->toDateString();
    }

    public static function currentSuspension(): ?self
    {
        return static::query()
            ->where('bookings_suspended', true)
            ->get()
            ->first(fn (self $settings): bool => $settings->bookingsAreSuspended());
    }

    public function suspensionMessage(): string
    {
        $message = filled($this->booking_suspension_message)
            ? $this->booking_suspension_message
            : __('site.bookings_suspended');

        return str_replace('{data_scadenza}', $this->booking_suspension_until?->format('d/m/Y') ?? '', $message);
    }
}
