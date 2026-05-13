<?php

namespace Tests\Feature;

use App\Models\ContactSetting;
use App\Models\LegalPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_page_is_public(): void
    {
        LegalPage::query()->updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'body' => '<p>Testo privacy personalizzato.</p>',
                'is_active' => true,
            ],
        );

        $this->get(route('legal.privacy'))
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Testo privacy personalizzato.');
    }

    public function test_cookie_policy_page_is_public(): void
    {
        LegalPage::query()->updateOrCreate(
            ['slug' => 'cookie-policy'],
            [
                'title' => 'Cookie Policy',
                'body' => '<p>Testo cookie personalizzato.</p>',
                'is_active' => true,
            ],
        );

        $this->get(route('legal.cookie'))
            ->assertOk()
            ->assertSee('Cookie Policy')
            ->assertSee('Testo cookie personalizzato.');
    }

    public function test_footer_links_to_managed_legal_pages_when_external_urls_are_empty(): void
    {
        ContactSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'business_name' => 'LAMAKA',
                'privacy_url' => null,
                'cookie_url' => null,
                'is_active' => true,
            ],
        );

        $this->get('/')
            ->assertOk()
            ->assertSee('/privacy-policy', false)
            ->assertSee('/cookie-policy', false);
    }

    public function test_legal_pages_show_footer_when_contact_settings_exist(): void
    {
        LegalPage::query()->updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'body' => '<p>Testo privacy personalizzato.</p>',
                'is_active' => true,
            ],
        );

        ContactSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'business_name' => 'LAMAKA',
                'heading' => 'Contatti',
                'instagram_url' => 'https://instagram.com/lamaka',
                'instagram_note' => '<p>Testo sotto Instagram.</p>',
                'legal_text' => '© Copyright LAMAKA',
                'is_active' => true,
            ],
        );

        $this->get(route('legal.privacy'))
            ->assertOk()
            ->assertSee('Contatti')
            ->assertSee('https://instagram.com/lamaka', false)
            ->assertSee('Testo sotto Instagram.')
            ->assertSee('Cookie policy')
            ->assertSee('© Copyright LAMAKA');
    }
}
