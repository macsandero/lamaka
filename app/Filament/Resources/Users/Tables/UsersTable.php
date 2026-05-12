<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean(),
                IconColumn::make('is_super_admin')
                    ->label('Super')
                    ->boolean(),
                IconColumn::make('must_set_password')
                    ->label('Primo accesso')
                    ->boolean(),
                TextColumn::make('setup_link')
                    ->label('Link primo accesso')
                    ->state(fn (User $record): ?string => $record->setup_token
                        ? route('admin.password-setup.edit', $record->setup_token)
                        : null)
                    ->copyable()
                    ->copyMessage('Link copiato')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('regenerateSetupLink')
                    ->label('Rigenera link')
                    ->icon('heroicon-o-key')
                    ->requiresConfirmation()
                    ->visible(fn (User $record): bool => ! $record->is_super_admin)
                    ->action(function (User $record): void {
                        $record->issuePasswordSetupToken();

                        Notification::make()
                            ->title('Link di primo accesso generato')
                            ->body(route('admin.password-setup.edit', $record->setup_token))
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([]);
    }
}
