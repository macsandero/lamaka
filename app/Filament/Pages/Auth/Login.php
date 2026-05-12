<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected string $view = 'filament.auth.login';

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
}
