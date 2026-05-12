<?php

namespace App\Filament\Resources\BookingFormFields\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingFormFieldsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
                TextColumn::make('label')
                    ->label('Etichetta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->label('Chiave')
                    ->toggleable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge(),
                IconColumn::make('is_required')
                    ->label('Obbl.')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Visibile')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
