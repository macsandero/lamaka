<?php

namespace Tests\Feature;

use App\Filament\Resources\BookingFormSettings\Pages\ManageBookingFormSettings;
use App\Models\BookingFormSetting;
use App\Models\Experience;
use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class BookingSuspensionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Mail::fake();
        config(['app.display_timezone' => 'Europe/Rome']);
    }

    public function test_suspension_blocks_all_website_requests_before_experience_or_date_validation(): void
    {
        $settings = $this->suspendBookings();

        foreach (['Primo incontro', 'Passeggiata al tramonto', 'Altro'] as $experience) {
            $this->post(route('booking.store'), ['fields' => ['esperienza' => $experience]])
                ->assertRedirect('/#prenota')
                ->assertSessionHasErrors(['booking' => $settings->booking_suspension_message]);
        }

        $this->assertDatabaseCount('booking_submissions', 0);
        Mail::assertNothingSent();
    }

    public function test_suspension_replaces_the_form_and_adds_the_click_notice_to_every_experience(): void
    {
        $settings = $this->suspendBookings();

        $this->get('/?esperienza=Primo%20incontro')
            ->assertOk()
            ->assertSee($settings->booking_suspension_message)
            ->assertSee('window.alert(', false)
            ->assertDontSee('class="booking-form', false)
            ->assertDontSee('id="booking-availability-calendar"', false);

        foreach (Experience::query()->published()->get() as $experience) {
            $this->get(route('experiences.show', $experience))
                ->assertOk()
                ->assertSee('Prenota questa esperienza')
                ->assertSee($settings->booking_suspension_message)
                ->assertSee('window.alert(', false);
        }
    }

    public function test_suspension_expires_at_the_end_of_the_selected_day_in_italy(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-19 21:59:59', 'UTC'));
        $this->suspendBookings(['booking_suspension_until' => '2026-10-19']);

        $this->post(route('booking.store'), $this->validRequest())
            ->assertSessionHasErrors('booking');
        $this->assertDatabaseCount('booking_submissions', 0);

        $this->travelTo(CarbonImmutable::parse('2026-10-19 22:00:00', 'UTC'));

        $this->get('/')
            ->assertOk()
            ->assertSee('class="booking-form', false)
            ->assertDontSee('Siamo chiusi per manutenzione.');

        $this->post(route('booking.store'), $this->validRequest())
            ->assertSessionHasNoErrors()
            ->assertSessionHas('booking_success');
        $this->assertDatabaseCount('booking_submissions', 1);
    }

    public function test_disabling_the_switch_reopens_bookings_immediately(): void
    {
        $settings = $this->suspendBookings();
        $settings->update(['bookings_suspended' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('class="booking-form', false)
            ->assertDontSee($settings->booking_suspension_message);

        $this->post(route('booking.store'), $this->validRequest())
            ->assertSessionHasNoErrors()
            ->assertSessionHas('booking_success');
        $this->assertDatabaseCount('booking_submissions', 1);
    }

    public function test_expiry_restores_the_experiences_weekday_restrictions(): void
    {
        $this->suspendBookings(['booking_suspension_until' => now()->subDay()->toDateString()]);
        Experience::query()->where('title', 'Primo incontro')->update(['available_weekdays' => []]);

        $this->post(route('booking.store'), $this->validRequest())
            ->assertSessionHasErrors('fields.data_ora_preferita');
        $this->assertDatabaseCount('booking_submissions', 0);
    }

    public function test_suspension_is_independent_of_the_form_visibility_setting_and_admin_agenda(): void
    {
        $this->suspendBookings(['is_active' => false]);
        $this->post(route('booking.store'), $this->validRequest())->assertSessionHasErrors('booking');

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post(route('agenda.store'), [
            'booking_date' => now()->addDays(2)->toDateString(),
            'animals' => 1,
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertDatabaseCount('booking_submissions', 1);
    }

    public function test_notice_supports_english_and_escapes_customer_facing_text(): void
    {
        $settings = $this->suspendBookings([
            'booking_suspension_message' => "Chiuso <script>alert('x')</script>\nTorniamo presto.",
        ]);

        $this->get('/')
            ->assertSee(e($settings->booking_suspension_message), false)
            ->assertDontSee("<script>alert('x')</script>", false);

        $settings->update(['translations' => ['en' => ['booking_suspension_message' => 'Bookings will resume soon.']]]);

        $this->withSession(['locale' => 'en'])->get('/')
            ->assertSee('Bookings will resume soon.');
        $this->withSession(['locale' => 'en'])->post(route('booking.store'), [])
            ->assertSessionHasErrors(['booking' => 'Bookings will resume soon.']);
    }

    public function test_expiry_date_tag_is_expanded_in_notices_and_tracks_date_changes(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-19', 'Europe/Rome'));
        $settings = $this->suspendBookings([
            'booking_suspension_until' => '2026-10-31',
            'booking_suspension_message' => 'Prenotazioni sospese fino al {data_scadenza} incluso.',
            'translations' => ['en' => ['booking_suspension_message' => 'Bookings suspended until {data_scadenza} inclusive.']],
        ]);

        $this->get('/')
            ->assertSee('Prenotazioni sospese fino al 31/10/2026 incluso.')
            ->assertDontSee('{data_scadenza}');
        $experience = Experience::query()->published()->firstOrFail();
        $this->get(route('experiences.show', $experience))
            ->assertSee(json_encode('Prenotazioni sospese fino al 31/10/2026 incluso.'), false);
        $this->post(route('booking.store'), [])
            ->assertSessionHasErrors(['booking' => 'Prenotazioni sospese fino al 31/10/2026 incluso.']);

        $settings->update(['booking_suspension_until' => '2026-11-15']);
        $this->withSession(['locale' => 'en'])->get('/')
            ->assertSee('Bookings suspended until 15/11/2026 inclusive.')
            ->assertDontSee('{data_scadenza}');
        $this->assertSame('Prenotazioni sospese fino al {data_scadenza} incluso.', $settings->fresh()->getRawOriginal('booking_suspension_message'));
    }

    public function test_admin_switch_shows_required_fields_and_persists_suspension(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $settings = BookingFormSetting::query()->firstOrFail();

        Livewire::test(ManageBookingFormSettings::class)
            ->mountTableAction('edit', $settings)
            ->assertFormFieldHidden('booking_suspension_until')
            ->assertFormFieldHidden('booking_suspension_message')
            ->fillForm(['bookings_suspended' => true])
            ->assertFormFieldVisible('booking_suspension_until')
            ->assertFormFieldVisible('booking_suspension_message')
            ->callMountedTableAction()
            ->assertHasTableActionErrors([
                'booking_suspension_until' => 'required',
                'booking_suspension_message' => 'required',
            ])
            ->fillForm([
                'booking_suspension_until' => now()->addWeek()->toDateString(),
                'booking_suspension_message' => 'Sospensione inserita dall’admin.',
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        $this->assertNotNull(BookingFormSetting::currentSuspension());
        $this->assertSame('Sospensione inserita dall’admin.', $settings->fresh()->booking_suspension_message);

        Livewire::test(ManageBookingFormSettings::class)
            ->callTableAction('edit', $settings, data: ['bookings_suspended' => false])
            ->assertHasNoTableActionErrors();

        $this->assertNull(BookingFormSetting::currentSuspension());
    }

    private function suspendBookings(array $values = []): BookingFormSetting
    {
        $settings = BookingFormSetting::query()->firstOrFail();
        $settings->update([
            'bookings_suspended' => true,
            'booking_suspension_until' => now()->addWeek()->toDateString(),
            'booking_suspension_message' => 'Siamo chiusi per manutenzione.',
            ...$values,
        ]);

        return $settings;
    }

    private function validRequest(): array
    {
        return ['fields' => [
            'nome' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'esperienza' => 'Primo incontro',
            'data_ora_preferita' => now()->addDays(3)->toDateString(),
            'privacy' => '1',
        ]];
    }
}
