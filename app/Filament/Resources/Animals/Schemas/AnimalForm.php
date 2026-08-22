<?php

namespace App\Filament\Resources\Animals\Schemas;

use App\Filament\Forms\EnglishContent;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Descrizione')
                    ->rows(4)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Immagine')
                    ->image()
                    ->disk('public')
                    ->directory('animals')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Ordine')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Visibile')
                    ->default(true),
                EnglishContent::section([
                    ['name', 'Nome'], ['description', 'Descrizione', 'textarea'],
                ]),
            ]);
    }
}
