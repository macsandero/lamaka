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
            'created_by_user_id' => $admin->id,
            'quantity' => 12,
            'unit_price' => 0.55,
            'total_price' => 6.60,
            'is_collected' => false,
        ]);
    }

    public function test_order_shows_the_initial_for_the_account_that_created_it(): void
    {
        $admin = User::factory()->create(['email' => 'vera.munzi@gmail.com', 'is_admin' => true]);
        $contact = EggContact::create(['first_name' => 'Mario', 'last_name' => 'Rossi', 'phone' => '123']);

        $this->actingAs($admin)->post(route('uova.orders.store'), [
            'egg_contact_id' => $contact->id,
            'order_date' => '2026-08-27',
            'quantity' => 6,
        ]);

        $this->actingAs($admin)->get(route('uova.index', ['date' => '2026-08-27', 'month' => '2026-08']))
            ->assertOk()
            ->assertSee('title="Inserito da vera.munzi@gmail.com">V</span>', false);
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

    public function test_cancelled_order_remains_visible_but_is_removed_from_totals(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $contact = EggContact::create(['first_name' => 'Elena', 'last_name' => 'Neri', 'phone' => '789']);
        EggDailyProduction::create(['production_date' => '2026-08-27', 'quantity' => 10]);
        $order = EggOrder::create([
            'egg_contact_id' => $contact->id, 'order_date' => '2026-08-27',
            'quantity' => 4, 'unit_price' => .40, 'total_price' => 1.60,
        ]);

        $this->actingAs($admin)->patch(route('uova.orders.toggle-cancelled', $order))->assertRedirect();
        $this->assertNotNull($order->fresh()->cancelled_at);

        $this->actingAs($admin)->get(route('uova.index', ['date' => '2026-08-27', 'month' => '2026-08']))
            ->assertOk()
            ->assertSee('Elena Neri')
            ->assertSee('Ripristina ordine')
            ->assertSee('>10<', false);
    }

    public function test_pending_reservations_are_shown_below_final_balance(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $contact = EggContact::create(['first_name' => 'Paolo', 'last_name' => 'Blu', 'phone' => '321']);
        EggDailyProduction::create(['production_date' => '2026-08-27', 'quantity' => 12]);
        EggOrder::create([
            'egg_contact_id' => $contact->id, 'order_date' => '2026-08-27',
            'quantity' => 5, 'unit_price' => .40, 'total_price' => 2.00,
        ]);

        $this->actingAs($admin)->get(route('uova.index', ['date' => '2026-08-27', 'month' => '2026-08']))
            ->assertOk()
            ->assertSee('(di cui 5 già prenotate)');
    }

    public function test_statistics_filter_sums_production_collected_sales_and_revenue(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $contact = EggContact::create(['first_name' => 'Sara', 'last_name' => 'Gialli', 'phone' => '555']);
        EggDailyProduction::create(['production_date' => '2026-08-10', 'quantity' => 9]);
        EggDailyProduction::create(['production_date' => '2026-08-11', 'quantity' => 11]);
        EggOrder::create([
            'egg_contact_id' => $contact->id, 'order_date' => '2026-08-10',
            'quantity' => 6, 'unit_price' => .50, 'total_price' => 3.00,
            'is_collected' => true, 'collected_at' => now(),
        ]);
        EggOrder::create([
            'egg_contact_id' => $contact->id, 'order_date' => '2026-08-11',
            'quantity' => 4, 'unit_price' => .50, 'total_price' => 2.00,
        ]);

        $this->actingAs($admin)->get(route('uova.index', [
            'stats_from' => '2026-08-10', 'stats_to' => '2026-08-11',
        ]))->assertOk()
            ->assertSee('Statistiche')
            ->assertSee('>20<', false)
            ->assertSee('>6<', false)
            ->assertSee('€ 3,00')
            ->assertSee('egg-stats-chart');
    }
}
