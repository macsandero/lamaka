<?php

namespace Tests\Feature;

use App\Filament\Resources\BookingSubmissions\Pages\EditBookingSubmission;
use App\Filament\Resources\BookingSubmissions\Pages\ListBookingSubmissions;
use App\Filament\Resources\BookingSubmissions\Pages\ViewBookingSubmission;
use App\Models\BookingSubmission;
use App\Models\Experience;
use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Facades\Filament;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class BookingConsistencyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_confirming_from_requests_updates_agenda_and_capacity(): void
    {
        $booking = $this->booking(['animals' => null]);

        Livewire::test(EditBookingSubmission::class, ['record' => $booking->getRouteKey()])
            ->fillForm(['status' => 'confirmed', 'animals' => 5])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertNotNull($booking->fresh()->confirmed_at);
        $this->assertContains($booking->booking_date->toDateString(), BookingSubmission::unavailableDates());
        $this->get(route('agenda.index', ['date' => $booking->booking_date->toDateString()]))
            ->assertOk()->assertSee('Animali confermati: 5/5')->assertDontSee('Da completare');
    }

    public function test_table_cancellation_releases_capacity_and_view_action_can_reconfirm(): void
    {
        $booking = $this->booking(['status' => 'confirmed', 'animals' => 5, 'confirmed_at' => now()]);
        $date = $booking->booking_date->toDateString();

        Livewire::test(ListBookingSubmissions::class)
            ->callTableAction('edit', $booking, data: [
                'status' => 'cancelled', 'cancellation_reason' => 'Altro',
                'cancellation_reason_other' => 'Cambio programmi.',
            ])->assertHasNoTableActionErrors();

        $booking->refresh();
        $this->assertNull($booking->confirmed_at);
        $this->assertNotNull($booking->cancelled_at);
        $this->assertSame(0, BookingSubmission::confirmedAnimalsForDate($date));
        $this->assertNotContains($date, BookingSubmission::unavailableDates());

        Livewire::test(ViewBookingSubmission::class, ['record' => $booking->getRouteKey()])
            ->callAction('edit', data: ['status' => 'confirmed'])
            ->assertHasNoActionErrors();

        $booking->refresh();
        $this->assertNotNull($booking->confirmed_at);
        $this->assertNull($booking->cancelled_at);
        $this->assertNull($booking->cancellation_reason);
        $this->assertNull($booking->cancellation_reason_other);
        $this->assertContains($date, BookingSubmission::unavailableDates());
    }

    public function test_all_requests_edit_paths_check_capacity_and_show_field_errors(): void
    {
        $this->booking(['status' => 'confirmed', 'animals' => 4, 'confirmed_at' => now()]);
        $booking = $this->booking(['animals' => 2]);

        Livewire::test(EditBookingSubmission::class, ['record' => $booking->getRouteKey()])
            ->fillForm(['status' => 'confirmed'])->call('save')->assertHasFormErrors(['animals']);
        Livewire::test(ListBookingSubmissions::class)
            ->callTableAction('edit', $booking, data: ['status' => 'confirmed'])
            ->assertHasTableActionErrors(['animals']);
        Livewire::test(ViewBookingSubmission::class, ['record' => $booking->getRouteKey()])
            ->callAction('edit', data: ['status' => 'confirmed'])
            ->assertHasActionErrors(['animals']);

        $this->assertSame('new', $booking->fresh()->status);
        $this->assertNull($booking->fresh()->confirmed_at);
    }

    public function test_confirmation_requires_a_day_and_positive_animal_count_and_cancellation_requires_reason(): void
    {
        $booking = $this->booking(['booking_date' => null, 'animals' => null]);

        Livewire::test(EditBookingSubmission::class, ['record' => $booking->getRouteKey()])
            ->fillForm(['status' => 'confirmed'])->call('save')
            ->assertHasFormErrors(['animals' => 'required', 'booking_date' => 'required']);

        Livewire::test(EditBookingSubmission::class, ['record' => $booking->getRouteKey()])
            ->fillForm(['status' => 'cancelled'])->call('save')
            ->assertHasFormErrors(['cancellation_reason' => 'required']);

        $this->post(route('agenda.store'), [
            'booking_date' => now()->addDays(3)->toDateString(), 'animals' => 0,
        ])->assertSessionHasErrors('animals');
    }

    public function test_non_confirmed_statuses_release_capacity_and_clear_confirmation(): void
    {
        foreach (['new', 'contacted', 'closed'] as $status) {
            $booking = $this->booking(['status' => 'confirmed', 'animals' => 5, 'confirmed_at' => now()]);
            Livewire::test(EditBookingSubmission::class, ['record' => $booking->getRouteKey()])
                ->fillForm(['status' => $status])->call('save')->assertHasNoFormErrors();

            $this->assertNull($booking->fresh()->confirmed_at);
            $this->assertSame(0, BookingSubmission::confirmedAnimalsForDate($booking->booking_date->toDateString()));
        }
    }

    public function test_agenda_create_update_and_confirmation_respect_experience_weekdays(): void
    {
        $experience = $this->experience(['available_weekdays' => [1]]);
        $monday = now()->addWeek()->startOfWeek();
        $tuesday = $monday->copy()->addDay();

        $this->post(route('agenda.store'), [
            'experience_id' => $experience->id, 'booking_date' => $tuesday->toDateString(), 'animals' => 1,
        ])->assertSessionHasErrors('booking_date');

        $this->post(route('agenda.store'), [
            'experience_id' => $experience->id, 'booking_date' => $monday->toDateString(), 'animals' => 1,
        ])->assertSessionHasNoErrors();
        $booking = BookingSubmission::query()->firstOrFail();

        $this->put(route('agenda.update', $booking), [
            'booking_date' => $tuesday->toDateString(), 'animals' => 1,
        ])->assertSessionHasErrors('booking_date');
        $this->assertSame($monday->toDateString(), $booking->fresh()->booking_date->toDateString());

        $pending = $this->booking(['experience_id' => $experience->id, 'booking_date' => $tuesday]);
        $this->post(route('agenda.confirm', $pending))->assertSessionHasErrors('booking_date');
        $this->assertNull($pending->fresh()->confirmed_at);
    }

    public function test_requests_cannot_confirm_unavailable_weekdays_even_after_experience_is_renamed(): void
    {
        $experience = $this->experience(['available_weekdays' => []]);
        $booking = $this->booking(['experience_id' => $experience->id]);
        $experience->update(['title' => 'Esperienza rinominata']);

        Livewire::test(EditBookingSubmission::class, ['record' => $booking->getRouteKey()])
            ->fillForm(['status' => 'confirmed'])->call('save')
            ->assertHasFormErrors(['booking_date']);

        $this->assertNull($booking->fresh()->confirmed_at);
    }

    public function test_public_request_stores_experience_id_and_uses_italian_day_at_utc_boundary(): void
    {
        $this->seed();
        Mail::fake();
        $this->travelTo(CarbonImmutable::parse('2026-10-19 22:30:00', 'UTC'));
        $fields = [
            'nome' => 'Mario Rossi', 'email' => 'mario@example.com',
            'esperienza' => 'Primo incontro', 'privacy' => '1',
        ];

        $this->post(route('booking.store'), ['fields' => [...$fields, 'data_ora_preferita' => '2026-10-20']])
            ->assertSessionHasErrors('fields.data_ora_preferita');
        $this->post(route('booking.store'), ['fields' => [...$fields, 'data_ora_preferita' => '2026-10-21']])
            ->assertSessionHasNoErrors();

        $this->assertSame(
            Experience::query()->where('title', 'Primo incontro')->value('id'),
            BookingSubmission::query()->firstOrFail()->experience_id,
        );
        $this->get('/')->assertSee('const todayValue = "2026-10-20";', false);
    }

    public function test_migration_links_legacy_experiences_and_repairs_inconsistent_timestamps(): void
    {
        $experience = $this->experience(['translations' => ['en' => ['title' => 'An experience']]]);
        $confirmed = $this->booking([
            'status' => 'confirmed', 'confirmed_at' => null,
            'data' => ['esperienza' => ['value' => 'An experience']],
        ]);
        $cancelled = $this->booking(['status' => 'cancelled', 'confirmed_at' => now(), 'cancelled_at' => null]);
        $closed = $this->booking(['status' => 'closed', 'confirmed_at' => now()]);

        Schema::table('booking_submissions', fn (Blueprint $table) => $table->dropConstrainedForeignId('experience_id'));
        $migration = require database_path('migrations/2026_10_19_010000_link_booking_experiences_and_sync_statuses.php');
        $migration->up();

        $this->assertSame($experience->id, $confirmed->fresh()->experience_id);
        $this->assertNotNull($confirmed->fresh()->confirmed_at);
        $this->assertNull($cancelled->fresh()->confirmed_at);
        $this->assertNotNull($cancelled->fresh()->cancelled_at);
        $this->assertNull($closed->fresh()->confirmed_at);
    }

    private function booking(array $values = []): BookingSubmission
    {
        return BookingSubmission::create([
            'reference' => uniqid('CONSISTENCY-'), 'data' => [], 'status' => 'new',
            'booking_date' => now()->addDays(3)->toDateString(), 'animals' => 1,
            'customer_name' => 'Mario Rossi', 'origin' => 'website', ...$values,
        ]);
    }

    private function experience(array $values = []): Experience
    {
        return Experience::create([
            'title' => 'Esperienza', 'description' => 'Descrizione',
            'is_active' => true, 'available_weekdays' => [1, 2, 3, 4, 5, 6, 7], ...$values,
        ]);
    }
}
