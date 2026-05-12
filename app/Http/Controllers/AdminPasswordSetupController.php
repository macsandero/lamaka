<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminPasswordSetupController extends Controller
{
    public function edit(string $token): View
    {
        $user = $this->findUserByValidToken($token);

        abort_unless($user, 404);

        return view('admin.set-password', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    public function update(Request $request, string $token): RedirectResponse
    {
        $user = $this->findUserByValidToken($token);

        abort_unless($user, 404);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
            'must_set_password' => false,
            'setup_token' => null,
            'setup_token_expires_at' => null,
        ])->save();

        return redirect()
            ->route('filament.admin.auth.login')
            ->with('status', 'Password impostata. Ora puoi accedere.');
    }

    private function findUserByValidToken(string $token): ?User
    {
        $user = User::query()
            ->where('setup_token', $token)
            ->first();

        if (! $user?->canUsePasswordSetupToken()) {
            return null;
        }

        return $user;
    }
}
