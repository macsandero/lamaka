<?php

namespace Tests\Feature;

use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperienceDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_experience_detail_shows_booking_link_with_selected_experience(): void
    {
        $experience = Experience::query()->create([
            'title' => 'Primo incontro',
            'description' => 'Descrizione breve',
            'experience_type' => 'Esperienza con alpaca',
            'purpose' => 'Finalità della scheda',
            'experience_details' => "conoscerai gli animali\ncamminerai nella natura",
            'short_duration' => '30 min',
            'short_price' => '20€ a persona',
            'long_duration' => '1 ora',
            'long_price' => '35€ a persona',
            'ideal_for' => 'Ideale per famiglie',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $this->get(route('experiences.show', $experience))
            ->assertOk()
            ->assertSee('Tipo esperienza')
            ->assertSee('Prenota questa esperienza')
            ->assertDontSee('Durante l’esperienza:', false)
            ->assertSee('/?esperienza=Primo%20incontro#prenota', false);
    }

    public function test_booking_form_preselects_experience_from_query_string(): void
    {
        Experience::query()->create([
            'title' => 'Primo incontro',
            'description' => 'Descrizione breve',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $this->seed();

        $this->get('/?esperienza=Primo%20incontro#prenota')
            ->assertOk()
            ->assertSee('<option value="Primo incontro" selected>Primo incontro</option>', false);
    }
}
