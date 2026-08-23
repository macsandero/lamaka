<?php

namespace Tests\Feature;

use App\Models\BookingSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_installable_calendar_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get(route('agenda.index'))
            ->assertOk()
            ->assertSee('Agenda prenotazioni')
            ->assertSee('Conferma prenotazione');

        $this->get(route('agenda.manifest'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/manifest+json');
    }

    public function test_bookings_remain_visible_when_month_is_passed_in_query_string(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $date = now()->addDays(3)->toDateString();
        BookingSubmission::create([
            'reference' => 'VISIBLE', 'data' => [], 'booking_date' => $date,
            'customer_name' => 'Cliente visibile', 'origin' => 'website', 'status' => 'new',
        ]);

        $this->actingAs($admin)->get(route('agenda.index', [
            'date' => $date,
            'month' => now()->addDays(3)->format('Y-m'),
        ]))->assertOk()->assertSee('Cliente visibile');
    }

    public function test_legacy_website_booking_preselects_website_source(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $booking = BookingSubmission::create([
            'reference' => 'WEBSITE', 'data' => [], 'booking_date' => now()->addDay(),
            'origin' => 'website', 'source' => null, 'status' => 'new',
        ]);

        $this->actingAs($admin)->get(route('agenda.index', [
            'date' => $booking->booking_date->toDateString(),
            'edit' => $booking->id,
        ]))->assertOk()->assertSee('<option selected>Sito web</option>', false);
    }

    public function test_admin_can_create_a_confirmed_booking_from_agenda(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('agenda.store'), [
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:30',
            'end_time' => '12:00',
            'participants' => 4,
            'animals' => 2,
            'customer_name' => 'Mario Rossi',
            'source' => 'Instagram',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('booking_submissions', [
            'customer_name' => 'Mario Rossi', 'animals' => 2,
            'start_time' => '10:30', 'end_time' => '12:00',
            'origin' => 'admin', 'status' => 'confirmed',
        ]);
        $this->assertNotNull(BookingSubmission::firstOrFail()->confirmed_at);
    }

    public function test_daily_animal_capacity_cannot_be_exceeded(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $date = now()->addDays(3)->toDateString();
        BookingSubmission::create([
            'reference' => 'EXISTING', 'data' => [], 'booking_date' => $date,
            'animals' => 4, 'origin' => 'admin', 'status' => 'confirmed', 'confirmed_at' => now(),
        ]);

        $this->actingAs($admin)->post(route('agenda.store'), [
            'booking_date' => $date, 'animals' => 2,
        ])->assertSessionHasErrors('animals');

        $this->assertDatabaseCount('booking_submissions', 1);
    }

    public function test_website_request_is_pending_and_full_dates_are_rejected(): void
    {
        $this->seed();
        $date = now()->addDays(4)->toDateString();
        BookingSubmission::create([
            'reference' => 'FULL', 'data' => [], 'booking_date' => $date,
            'animals' => 5, 'origin' => 'admin', 'status' => 'confirmed', 'confirmed_at' => now(),
        ]);

        $this->post(route('booking.store'), ['fields' => [
            'nome' => 'Cliente', 'email' => 'cliente@example.com', 'esperienza' => 'Primo incontro',
            'data_ora_preferita' => $date, 'privacy' => '1',
        ]])->assertSessionHasErrors('fields.data_ora_preferita');

        $this->assertDatabaseCount('booking_submissions', 1);
    }

    public function test_cancelling_a_booking_keeps_it_visible_and_releases_its_animals(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $date = now()->addDays(5)->toDateString();
        $booking = BookingSubmission::create([
            'reference' => 'TO-CANCEL', 'data' => [], 'booking_date' => $date,
            'animals' => 5, 'customer_name' => 'Cliente annullato',
            'origin' => 'admin', 'status' => 'confirmed', 'confirmed_at' => now(),
        ]);

        $this->assertContains($date, BookingSubmission::unavailableDates());

        $this->actingAs($admin)->post(route('agenda.cancel', $booking), [
            'cancellation_reason' => 'Condizioni meteo',
        ])->assertRedirect();

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertNull($booking->confirmed_at);
        $this->assertNotNull($booking->cancelled_at);
        $this->assertNotContains($date, BookingSubmission::unavailableDates());

        $this->actingAs($admin)->get(route('agenda.index', ['date' => $date]))
            ->assertOk()->assertSee('Cliente annullato')->assertSee('Annullata')->assertSee('Condizioni meteo');
    }

    public function test_modifying_date_recalculates_capacity_on_both_days(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $oldDate = now()->addDays(6)->toDateString();
        $newDate = now()->addDays(7)->toDateString();
        $booking = BookingSubmission::create([
            'reference' => 'TO-MOVE', 'data' => [], 'booking_date' => $oldDate,
            'animals' => 5, 'origin' => 'admin', 'status' => 'confirmed', 'confirmed_at' => now(),
        ]);

        $this->actingAs($admin)->put(route('agenda.update', $booking), [
            'booking_date' => $newDate, 'start_time' => '14:00', 'end_time' => '15:30', 'animals' => 5,
        ])->assertRedirect();

        $this->assertNotContains($oldDate, BookingSubmission::unavailableDates());
        $this->assertContains($newDate, BookingSubmission::unavailableDates());
        $booking->refresh();
        $this->assertSame($newDate, $booking->booking_date->toDateString());
        $this->assertSame('14:00', $booking->start_time);
        $this->assertSame('15:30', $booking->end_time);
    }

    public function test_cancellation_with_other_reason_requires_details(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $booking = BookingSubmission::create([
            'reference' => 'OTHER-REASON', 'data' => [], 'booking_date' => now()->addDays(3),
            'animals' => 1, 'origin' => 'admin', 'status' => 'confirmed', 'confirmed_at' => now(),
        ]);

        $this->actingAs($admin)->post(route('agenda.cancel', $booking), [
            'cancellation_reason' => 'Altro',
        ])->assertSessionHasErrors('cancellation_reason_other');

        $this->assertNull($booking->fresh()->cancelled_at);
    }

    public function test_pending_booking_can_be_confirmed_directly_from_summary_with_capacity_check(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $date = now()->addDays(4)->toDateString();
        $booking = BookingSubmission::create([
            'reference' => 'PENDING', 'data' => [], 'booking_date' => $date,
            'animals' => 3, 'origin' => 'website', 'status' => 'new',
        ]);

        $this->actingAs($admin)->get(route('agenda.index', ['date' => $date]))
            ->assertOk()->assertSee('Conferma prenotazione')->assertSee('Modifica prenotazione')->assertSee('Annulla prenotazione')
            ->assertSee('showModal()', false)->assertSee('booking-dialog')->assertSee('edit-'.$booking->id);

        $this->actingAs($admin)->post(route('agenda.confirm', $booking))
            ->assertRedirect(route('agenda.index', ['date' => $date, 'month' => substr($date, 0, 7)]).'#day-details');
        $this->assertNotNull($booking->fresh()->confirmed_at);
        $this->assertSame(3, BookingSubmission::confirmedAnimalsForDate($date));

        $fullDate = now()->addDays(8)->toDateString();
        BookingSubmission::create([
            'reference' => 'ALMOST-FULL', 'data' => [], 'booking_date' => $fullDate,
            'animals' => 4, 'origin' => 'admin', 'status' => 'confirmed', 'confirmed_at' => now(),
        ]);
        $blocked = BookingSubmission::create([
            'reference' => 'BLOCKED-PENDING', 'data' => [], 'booking_date' => $fullDate,
            'animals' => 2, 'origin' => 'website', 'status' => 'new',
        ]);

        $this->actingAs($admin)->post(route('agenda.confirm', $blocked))->assertSessionHasErrors('animals');
        $this->assertNull($blocked->fresh()->confirmed_at);
    }
}
