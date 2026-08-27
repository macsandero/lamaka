<?php

namespace Tests\Feature;

use App\Models\EggContact;
use App\Models\EggDailyProduction;
use App\Models\EggOrder;
use App\Models\EggSaleSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EggSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_egg_sales_page_is_available_only_to_admins(): void
    {
        $this->get(route('uova.index'))->assertRedirect('/admin/login');

        $user = User::factory()->create();
        $this->actingAs($user)->get(route('uova.index'))->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get(route('uova.index'))
            ->assertOk()
            ->assertSee('Vendita uova')
            ->assertSee('Rubrica')
            ->assertSee('Impostazioni vendita uova');
    }

    public function test_admin_can_create_contact_and_order_with_current_price(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        EggSaleSetting::current()->update(['unit_price' => 0.55]);

        $this->actingAs($admin)->post(route('uova.contacts.store'), [
            'first_name' => 'Mario', 'last_name' => 'Rossi', 'phone' => '333 1234567',
        ])->assertRedirect();
        $contact = EggContact::firstOrFail();

        $this->actingAs($admin)->post(route('uova.orders.store'), [
            'egg_contact_id' => $contact->id,
            'order_date' => '2026-08-27',
            'quantity' => 12,
        ])->assertRedirect();

        $this->assertDatabaseHas('egg_orders', [
            'egg_contact_id' => $contact->id,
            'quantity' => 12,
            'unit_price' => 0.55,
            'total_price' => 6.60,
            'is_collected' => false,
        ]);
    }

    public function test_daily_balance_is_carried_forward_and_collection_changes_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $contact = EggContact::create(['first_name' => 'Anna', 'last_name' => 'Verdi', 'phone' => '123']);
        EggDailyProduction::create(['production_date' => '2026-08-26', 'quantity' => 10]);
        EggDailyProduction::create(['production_date' => '2026-08-27', 'quantity' => 4]);
        $order = EggOrder::create([
            'egg_contact_id' => $contact->id, 'order_date' => '2026-08-26',
            'quantity' => 7, 'unit_price' => .40, 'total_price' => 2.80,
        ]);

        $this->actingAs($admin)->get(route('uova.index', ['date' => '2026-08-27', 'month' => '2026-08']))
            ->assertOk()
            ->assertSee('Riporto iniziale')
            ->assertSee('Rimanenza finale')
            ->assertSee('>3<', false)
            ->assertSee('>7<', false);

        $this->actingAs($admin)->patch(route('uova.orders.toggle-collected', $order))->assertRedirect();
        $this->assertTrue($order->fresh()->is_collected);
        $this->assertNotNull($order->fresh()->collected_at);
    }

    public function test_negative_balance_is_visible_and_carried_to_next_day(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $contact = EggContact::create(['first_name' => 'Luca', 'last_name' => 'Bianchi', 'phone' => '456']);
        EggDailyProduction::create(['production_date' => '2026-08-27', 'quantity' => 5]);
        EggOrder::create([
            'egg_contact_id' => $contact->id, 'order_date' => '2026-08-27',
            'quantity' => 8, 'unit_price' => .40, 'total_price' => 3.20,
        ]);

        $this->actingAs($admin)->get(route('uova.index', ['date' => '2026-08-28', 'month' => '2026-08']))
            ->assertOk()
            ->assertSee('Uova mancanti')
            ->assertSee('>-3<', false);
    }
}
