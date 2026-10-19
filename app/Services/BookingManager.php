<?php

namespace App\Services;

use App\Models\BookingSubmission;
use App\Models\Experience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingManager
{
    public function save(BookingSubmission $booking, array $values = [], string $statePath = ''): BookingSubmission
    {
        try {
            return DB::transaction(function () use ($booking, $values): BookingSubmission {
                $booking->fill($values);
                $confirmed = $booking->status === 'confirmed';

                if ($booking->status !== 'cancelled') {
                    $booking->cancellation_reason = null;
                    $booking->cancellation_reason_other = null;
                }

                Validator::make($booking->getAttributes(), [
                    'status' => ['required', Rule::in(array_keys(BookingSubmission::STATUSES))],
                    'booking_date' => [Rule::requiredIf($confirmed), 'nullable', 'date'],
                    'experience_id' => ['nullable', Rule::exists('experiences', 'id')],
                    'animals' => [Rule::requiredIf($confirmed), 'nullable', 'integer', $confirmed ? 'min:1' : 'min:0', 'max:5'],
                    'cancellation_reason' => ['required_if:status,cancelled', 'nullable', Rule::in(BookingSubmission::CANCELLATION_REASONS)],
                    'cancellation_reason_other' => ['required_if:cancellation_reason,Altro', 'nullable', 'string', 'max:500'],
                ], [], [
                    'booking_date' => 'giorno', 'animals' => 'numero animali',
                    'cancellation_reason' => 'motivo annullamento',
                    'cancellation_reason_other' => 'dettagli annullamento',
                ])->validate();

                if ($confirmed) {
                    $experience = $booking->experience_id ? Experience::find($booking->experience_id) : null;
                    if ($experience && ! $experience->isAvailableOn($booking->booking_date->isoWeekday())) {
                        throw ValidationException::withMessages([
                            'booking_date' => 'L’esperienza scelta non è disponibile nel giorno selezionato.',
                        ]);
                    }

                    BookingSubmission::query()->whereDate('booking_date', $booking->booking_date)->lockForUpdate()->get();
                    $used = BookingSubmission::confirmedAnimalsForDate(
                        $booking->booking_date->toDateString(),
                        $booking->exists ? $booking->getKey() : null,
                    );

                    if ($used + $booking->animals > BookingSubmission::MAX_DAILY_ANIMALS) {
                        throw ValidationException::withMessages([
                            'animals' => 'Per questo giorno restano disponibili '.max(0, BookingSubmission::MAX_DAILY_ANIMALS - $used).' animali.',
                        ]);
                    }
                }

                $booking->confirmed_at = $confirmed ? ($booking->confirmed_at ?? now()) : null;
                $booking->cancelled_at = $booking->status === 'cancelled' ? ($booking->cancelled_at ?? now()) : null;

                if ($booking->cancellation_reason !== 'Altro') {
                    $booking->cancellation_reason_other = null;
                }

                $booking->save();

                return $booking;
            });
        } catch (ValidationException $exception) {
            if ($statePath === '') {
                throw $exception;
            }

            throw ValidationException::withMessages(collect($exception->errors())
                ->mapWithKeys(fn (array $messages, string $field): array => ["{$statePath}.{$field}" => $messages])
                ->all());
        }
    }
}
