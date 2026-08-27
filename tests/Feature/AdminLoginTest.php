<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_manual_login_route(): void
    {
        $user = User::factory()->create([
            'email' => 'macsandero@gmail.com',
            'password' => Hash::make('secret-password'),
            'is_admin' => true,
            'is_super_admin' => true,
            'must_set_password' => false,
        ]);

        $response = $this->post(route('admin.manual-login'), [
            'email' => ' MACSANDERO@GMAIL.COM ',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect();
        $response->assertCookie(Filament::auth()->getRecallerName());
        $this->assertAuthenticatedAs($user);
    }

    public function test_manual_login_returns_visible_error_for_bad_credentials(): void
    {
        $response = $this->from('/admin/login')->post(route('admin.manual-login'), [
            'email' => 'macsandero@gmail.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('email');
    }
}
