<?php

namespace App\Filament\Resources\BookingSubmissions\Tables;

use App\Models\BookingSubmission;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->label('Rif.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->state(fn ($record): ?string => $record->fieldValue('nome') ?? $record->fieldValue('name'))
                    ->searchable(query: fn ($query, string $search) => $query->where('data', 'like', "%{$search}%")),
                TextColumn::make('email')
                    ->label('Email')
                    ->state(fn ($record): ?string => $record->fieldValue('email'))
                    ->searchable(query: fn ($query, string $search) => $query->where('data', 'like', "%{$search}%")),
                TextColumn::make('status')
                    ->label('Stato')
                    ->formatStateUsing(fn (string $state): string => BookingSubmission::STATUSES[$state] ?? $state)
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Ricevuta')
                    ->state(fn (BookingSubmission $record): string => $record->receivedAtFormatted())
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Stato')
                    ->options(BookingSubmission::STATUSES),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
