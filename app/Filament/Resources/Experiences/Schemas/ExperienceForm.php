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
                    ->label('Descrizione')
                    ->rows(4)
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
