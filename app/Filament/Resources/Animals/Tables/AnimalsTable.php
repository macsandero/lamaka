<?php

namespace App\Filament\Resources\Animals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnimalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Immagine')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
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
