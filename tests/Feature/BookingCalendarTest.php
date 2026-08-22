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
}
