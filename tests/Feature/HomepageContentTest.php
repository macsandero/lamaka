<?php

namespace Tests\Feature;

use App\Models\HomepageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_title_uses_manual_line_breaks(): void
    {
        HomepageContent::query()->create([
            'hero_title' => "Passeggiate in natura con\nlama e alpaca",
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Passeggiate in natura con', false)
            ->assertSee('<br>', false)
            ->assertSee('lama e alpaca', false);
    }
}
