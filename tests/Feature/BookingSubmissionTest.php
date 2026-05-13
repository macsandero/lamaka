<?php

namespace Tests\Feature;

use App\Mail\BookingSubmissionReceived;
use App\Models\BookingSubmission;
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
                'data_ora_preferita' => now()->addDays(2)->setTime(10, 0)->format('Y-m-d\TH:i'),
                'partecipanti' => '2',
                'messaggio' => 'Vorrei informazioni sugli orari.',
                'privacy' => '1',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('booking_success');

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

    public function test_booking_request_cannot_use_today_as_preferred_datetime(): void
    {
        Mail::fake();

        $this->seed();

        $response = $this->from('/#prenota')->post(route('booking.store'), [
            'fields' => [
                'nome' => 'Mario Rossi',
                'email' => 'mario@example.com',
                'esperienza' => 'Primo incontro',
                'data_ora_preferita' => now()->setTime(18, 0)->format('Y-m-d\TH:i'),
                'privacy' => '1',
            ],
        ]);

        $response->assertRedirect('/#prenota');
        $response->assertSessionHasErrors('fields.data_ora_preferita');
        $this->assertDatabaseCount(BookingSubmission::class, 0);
        Mail::assertNothingSent();
    }

    public function test_booking_datetime_field_has_mobile_safe_class(): void
    {
        $this->seed();

        $this->get('/#prenota')
            ->assertOk()
            ->assertSee('booking-datetime-input', false);
    }
}
