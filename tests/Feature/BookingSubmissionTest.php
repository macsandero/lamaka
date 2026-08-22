<?php

namespace Tests\Feature;

use App\Mail\BookingSubmissionReceived;
use App\Models\BookingSubmission;
use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_request_can_be_submitted(): void
    {
        Mail::fake();
        config(['app.display_timezone' => 'Europe/Rome']);
        config(['mail.booking_to' => 'federicotoson07@gmail.com,vera.munzi@gmail.com']);

        $this->seed();

        $response = $this->post(route('booking.store'), [
            'fields' => [
                'nome' => 'Mario Rossi',
                'email' => 'mario@example.com',
                'telefono' => '+39 333 1234567',
                'esperienza' => 'Primo incontro',
                'data_ora_preferita' => now()->addDays(2)->toDateString(),
                'partecipanti' => '2',
                'messaggio' => 'Vorrei informazioni sugli orari.',
                'privacy' => '1',
            ],
        ]);

        $response->assertRedirect('/#prenota');
        $response->assertSessionHas('booking_success', "Richiesta inviata correttamente. Sarai contattato al più presto per concordare l'orario dell'attività");

        $this->assertDatabaseCount(BookingSubmission::class, 1);
        $this->assertDatabaseHas('booking_submissions', [
            'status' => 'new',
        ]);

        $submission = BookingSubmission::query()->firstOrFail();

        $this->assertSame('Mario Rossi', $submission->fieldValue('nome'));
        $this->assertSame('mario@example.com', $submission->fieldValue('email'));
        $this->assertSame(
            $submission->created_at->copy()->timezone('Europe/Rome')->format('d/m/Y H:i'),
            $submission->receivedAtFormatted(),
        );

        Mail::assertSent(BookingSubmissionReceived::class, fn (BookingSubmissionReceived $mail): bool => $mail->hasTo('federicotoson07@gmail.com')
            && $mail->hasTo('vera.munzi@gmail.com')
            && $mail->submission->is($submission));
    }

    public function test_booking_request_accepts_an_active_experience_created_from_admin(): void
    {
        Mail::fake();

        $this->seed();

        Experience::query()->create([
            'title' => 'Custode per un giorno',
            'description' => '<p>Esperienza educativa.</p>',
            'sort_order' => 50,
            'is_active' => true,
        ]);

        $response = $this->post(route('booking.store'), [
            'fields' => [
                'nome' => 'Mario Rossi',
                'email' => 'mario@example.com',
                'esperienza' => 'Custode per un giorno',
                'data_ora_preferita' => now()->addDays(2)->toDateString(),
                'privacy' => '1',
            ],
        ]);

        $response->assertRedirect('/#prenota');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('booking_success');

        $submission = BookingSubmission::query()->firstOrFail();

        $this->assertSame('Custode per un giorno', $submission->fieldValue('esperienza'));
        Mail::assertSent(BookingSubmissionReceived::class);
    }

    public function test_legacy_autofilled_website_field_does_not_block_booking_submission(): void
    {
        Mail::fake();

        $this->seed();

        $response = $this->post(route('booking.store'), [
            'website' => 'https://lamaka.it',
            'fields' => [
                'nome' => 'Mario Rossi',
                'email' => 'mario@example.com',
                'telefono' => '+39 333 1234567',
                'esperienza' => 'Primo incontro',
                'data_ora_preferita' => now()->addDays(2)->toDateString(),
                'partecipanti' => '2',
                'messaggio' => 'Vorrei informazioni sugli orari.',
                'privacy' => '1',
            ],
        ]);

        $response->assertRedirect('/#prenota');
        $response->assertSessionHas('booking_success');

        $this->assertDatabaseCount(BookingSubmission::class, 1);
        Mail::assertSent(BookingSubmissionReceived::class);
    }

    public function test_booking_honeypot_field_blocks_submission_without_saving(): void
    {
        Mail::fake();

        $this->seed();

        $response = $this->post(route('booking.store'), [
            'lamaka_confirm_url' => 'https://spam.example',
            'fields' => [
                'nome' => 'Mario Rossi',
                'email' => 'mario@example.com',
                'esperienza' => 'Primo incontro',
                'data_ora_preferita' => now()->addDays(2)->toDateString(),
                'privacy' => '1',
            ],
        ]);

        $response->assertRedirect('/#prenota');
        $response->assertSessionHas('booking_success');

        $this->assertDatabaseCount(BookingSubmission::class, 0);
        Mail::assertNothingSent();
    }

    public function test_booking_request_cannot_use_today_as_preferred_date(): void
    {
        Mail::fake();

        $this->seed();

        $response = $this->from('/#prenota')->post(route('booking.store'), [
            'fields' => [
                'nome' => 'Mario Rossi',
                'email' => 'mario@example.com',
                'esperienza' => 'Primo incontro',
                'data_ora_preferita' => now()->toDateString(),
                'privacy' => '1',
            ],
        ]);

        $response->assertRedirect('/#prenota');
        $response->assertSessionHasErrors('fields.data_ora_preferita');
        $this->assertDatabaseCount(BookingSubmission::class, 0);
        Mail::assertNothingSent();
    }

    public function test_booking_form_uses_date_only_and_shows_success_alert(): void
    {
        $this->seed();

        $this->get('/#prenota')
            ->assertOk()
            ->assertSee('Giorno preferito')
            ->assertSee('booking-availability-calendar', false)
            ->assertSee('type="hidden" name="fields[data_ora_preferita]"', false)
            ->assertDontSee('type="datetime-local"', false);

        $this->withSession([
            'booking_success' => "Richiesta inviata correttamente. Sarai contattato al più presto per concordare l'orario dell'attività",
        ])->get('/#prenota')
            ->assertOk()
            ->assertSee('window.alert(', false)
            ->assertSee("Richiesta inviata correttamente. Sarai contattato al più presto per concordare l'orario dell'attività");
    }

    public function test_booking_request_must_use_an_available_weekday_for_experience(): void
    {
        Mail::fake();
        $this->seed();
        $monday = now()->addWeek()->startOfWeek();

        $experience = Experience::query()->where('title', 'Primo incontro')->firstOrFail();
        $experience->update(['available_weekdays' => [2]]);

        $this->post(route('booking.store'), ['fields' => [
            'nome' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'esperienza' => 'Primo incontro',
            'data_ora_preferita' => $monday->toDateString(),
            'privacy' => '1',
        ]])->assertSessionHasErrors('fields.data_ora_preferita');

        $this->assertDatabaseCount('booking_submissions', 0);
    }

    public function test_experience_is_required_before_choosing_a_booking_date(): void
    {
        Mail::fake();
        $this->seed();

        $this->post(route('booking.store'), ['fields' => [
            'nome' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'data_ora_preferita' => now()->addDays(3)->toDateString(),
            'privacy' => '1',
        ]])->assertSessionHasErrors('fields.esperienza');

        $this->get('/#prenota')
            ->assertOk()
            ->assertSee('chooseExperience');
    }

    public function test_privacy_booking_field_links_to_legal_page(): void
    {
        $this->seed();

        $this->get('/#prenota')
            ->assertOk()
            ->assertSee('Ho letto l’', false)
            ->assertSee('href="http://localhost/privacy-policy"', false)
            ->assertSee('Privacy Policy')
            ->assertSee('acconsento al trattamento dei miei dati per la gestione della richiesta.');
    }

    public function test_booking_experience_options_are_sorted_with_other_last(): void
    {
        $this->seed();

        $this->get('/#prenota')
            ->assertOk()
            ->assertSeeInOrder([
                '<option value="Passeggiata al tramonto"',
                '<option value="Primo incontro"',
                '<option value="Altro"',
            ], false);
    }
}
