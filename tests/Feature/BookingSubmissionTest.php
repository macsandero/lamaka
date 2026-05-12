<?php

namespace Tests\Feature;

use App\Models\BookingSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_request_can_be_submitted(): void
    {
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
    }

    public function test_booking_request_cannot_use_today_as_preferred_datetime(): void
    {
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
    }
}
