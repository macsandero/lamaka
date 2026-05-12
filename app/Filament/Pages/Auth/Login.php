<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;

class Login extends BaseLogin
{
    protected string $view = 'filament.auth.login';

    public ?string $loginError = null;

    public function authenticate(): ?LoginResponse
    {
        $this->loginError = null;

        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            return $this->failLogin('Troppi tentativi di accesso. Riprova tra qualche minuto.');
        }

        $data = $this->form->getState();
        $credentials = $this->getCredentialsFromFormData($data);

        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check((string) $credentials['password'], (string) $user->password)) {
            return $this->failLogin('Email o password non corretti.');
        }

        if (! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel())) {
            return $this->failLogin('Questo utente non è abilitato all’area admin.');
        }

        if ($user->must_set_password) {
            return $this->failLogin('Questo utente deve prima impostare la password dal link di primo accesso.');
        }

        Filament::auth()->login($user, $data['remember'] ?? false);
        session()->regenerate();

        return app(LoginResponse::class);
    }

    public function getTitle(): string|Htmlable
    {
        return 'Accesso admin';
    }

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->email()
            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? strtolower(trim($state)) : $state)
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->autocomplete('current-password')
            ->required();
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('Accedi')
            ->submit('authenticate');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => strtolower(trim((string) $data['email'])),
            'password' => $data['password'],
        ];
    }

    private function failLogin(string $message): null
    {
        $this->loginError = $message;
        $this->addError('data.email', $message);
        $this->data['password'] = null;

        return null;
    }
}
