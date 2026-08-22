<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    use HasSaveConfirmation;

    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            ...$data,
            'password' => Hash::make(Str::password(32)),
            'is_admin' => true,
            'is_super_admin' => false,
            'must_set_password' => true,
            'setup_token' => Str::random(64),
            'setup_token_expires_at' => now()->addDays(7),
        ];
    }
}
