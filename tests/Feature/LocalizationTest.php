<?php

namespace Tests\Feature;

use App\Models\HomepageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_switch_translates_menu_form_and_managed_content(): void
    {
        $this->seed();
        HomepageContent::query()->firstOrFail()->update([
            'translations' => ['en' => ['hero_title' => 'Walks in the Dolomites']],
        ]);

        $this->get(route('language.switch', 'en'))->assertRedirect();

        $this->get('/')
            ->assertOk()
            ->assertSee('Walks in the Dolomites')
            ->assertSee('Experiences')
            ->assertSee('About us')
            ->assertSee('Select an experience first to view the available dates.');
    }

    public function test_italian_content_is_used_when_english_field_is_empty(): void
    {
        $this->seed();
        $this->withSession(['locale' => 'en'])->get('/')
            ->assertOk()
            ->assertSee('Passeggiate nella natura con lama e alpaca');
    }
}
