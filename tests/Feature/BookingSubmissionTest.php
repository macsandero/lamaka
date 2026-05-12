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
                'data_preferita' => '2026-06-01',
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
}
