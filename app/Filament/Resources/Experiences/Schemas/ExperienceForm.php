<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titolo')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Descrizione breve')
                    ->rows(4)
                    ->columnSpanFull(),
                TextInput::make('experience_type')
                    ->label('Tipo esperienza')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('purpose')
                    ->label('Finalità')
                    ->rows(4)
                    ->columnSpanFull(),
                Textarea::make('experience_details')
                    ->label('Durante l’esperienza')
                    ->helperText('Inserisci un punto per riga.')
                    ->rows(5)
                    ->columnSpanFull(),
                TextInput::make('short_duration')
                    ->label('Durata 1')
                    ->maxLength(255),
                TextInput::make('short_price')
                    ->label('Prezzo 1')
                    ->maxLength(255),
                TextInput::make('long_duration')
                    ->label('Durata 2')
                    ->maxLength(255),
                TextInput::make('long_price')
                    ->label('Prezzo 2')
                    ->maxLength(255),
                Textarea::make('ideal_for')
                    ->label('Ideale per')
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Immagine')
                    ->image()
                    ->disk('public')
                    ->directory('experiences')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Ordine')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Visibile')
                    ->default(true),
            ]);
    }
}
