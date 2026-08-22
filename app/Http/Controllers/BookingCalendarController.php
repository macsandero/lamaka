<?php

namespace App\Http\Controllers;

use App\Models\BookingSubmission;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingCalendarController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $selectedDate = $request->date('date')?->toDateString() ?? now()->toDateString();
        $month = $request->date('month')?->startOfMonth() ?? CarbonImmutable::parse($selectedDate)->startOfMonth();
        $editBooking = $request->integer('edit')
            ? BookingSubmission::query()->findOrFail($request->integer('edit'))
            : null;

        $monthBookings = BookingSubmission::query()
            ->whereBetween('booking_date', [$month->startOfMonth(), $month->endOfMonth()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn (BookingSubmission $booking) => $booking->booking_date?->toDateString());

        return view('admin.booking-calendar', [
            'month' => CarbonImmutable::parse($month),
            'selectedDate' => $selectedDate,
            'monthBookings' => $monthBookings,
            'dayBookings' => $monthBookings->get($selectedDate, collect()),
            'editBooking' => $editBooking,
            'sources' => BookingSubmission::SOURCES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);
        $values = $this->validated($request);

        $booking = new BookingSubmission([
            ...$values,
            'reference' => 'LAMAKA-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'data' => [],
            'status' => 'confirmed',
            'origin' => 'admin',
            'confirmed_at' => now(),
        ]);

        $this->saveWithinCapacity($booking);

        return $this->successRedirect($booking, 'Prenotazione confermata e salvata.');
    }

    public function update(Request $request, BookingSubmission $bookingSubmission): RedirectResponse
    {
        $this->ensureAdmin($request);
        $bookingSubmission->fill($this->validated($request));
        $bookingSubmission->status = 'confirmed';
        $bookingSubmission->confirmed_at = now();
        $this->saveWithinCapacity($bookingSubmission);

        return $this->successRedirect($bookingSubmission, 'Prenotazione completata e confermata.');
    }

    private function validated(Request $request): array
    {
        $values = $request->validate([
            'booking_date' => ['required', 'date'],
            'participants' => ['nullable', 'integer', 'min:0'],
            'animals' => ['nullable', 'integer', 'min:0', 'max:5'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['nullable', Rule::in(BookingSubmission::SOURCES)],
            'source_other' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if (($values['source'] ?? null) !== 'Altro') {
            $values['source_other'] = null;
        }

        return $values;
    }

    private function saveWithinCapacity(BookingSubmission $booking): void
    {
        DB::transaction(function () use ($booking): void {
            BookingSubmission::query()
                ->whereDate('booking_date', $booking->booking_date)
                ->lockForUpdate()->get();

            $used = BookingSubmission::confirmedAnimalsForDate(
                CarbonImmutable::parse($booking->booking_date)->toDateString(),
                $booking->exists ? $booking->getKey() : null,
            );

            if ($used + (int) ($booking->animals ?? 0) > BookingSubmission::MAX_DAILY_ANIMALS) {
                throw ValidationException::withMessages([
                    'animals' => 'Per questo giorno restano disponibili '.max(0, BookingSubmission::MAX_DAILY_ANIMALS - $used).' animali.',
                ]);
            }

            $booking->save();
        });
    }

    private function successRedirect(BookingSubmission $booking, string $message): RedirectResponse
    {
        return redirect()->route('agenda.index', [
            'date' => $booking->booking_date?->toDateString(),
            'month' => $booking->booking_date?->format('Y-m'),
        ])->with('success', $message);
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->is_admin || $request->user()?->is_super_admin, 403);
    }
}
