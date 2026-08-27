<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower(trim($validated['email']));
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (! $user || ! Hash::check($validated['password'], (string) $user->password)) {
            return back()
                ->withInput(['email' => $email])
                ->withErrors(['email' => 'Email o password non corretti.']);
        }

        if (! $user->canAccessPanel(Filament::getPanel('admin'))) {
            return back()
                ->withInput(['email' => $email])
                ->withErrors(['email' => 'Questo utente non è abilitato all’area admin.']);
        }

        if ($user->must_set_password) {
            return back()
                ->withInput(['email' => $email])
                ->withErrors(['email' => 'Questo utente deve prima impostare la password dal link di primo accesso.']);
        }

        Filament::auth()->login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(Filament::getPanel('admin')->getUrl());
    }
}
