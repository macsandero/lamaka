<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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
                RichEditor::make('description')
                    ->label('Descrizione breve')
                    ->columnSpanFull(),
                RichEditor::make('experience_type')
                    ->label('Tipo esperienza')
                    ->columnSpanFull(),
                RichEditor::make('purpose')
                    ->label('Finalità')
                    ->columnSpanFull(),
                RichEditor::make('experience_details')
                    ->label('Durante l’esperienza')
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
                TextInput::make('third_duration')
                    ->label('Durata 3')
                    ->maxLength(255),
                TextInput::make('third_price')
                    ->label('Prezzo 3')
                    ->maxLength(255),
                TextInput::make('fourth_duration')
                    ->label('Durata 4')
                    ->maxLength(255),
                TextInput::make('fourth_price')
                    ->label('Prezzo 4')
                    ->maxLength(255),
                Textarea::make('duration_notes')
                    ->label('Note')
                    ->helperText('Inserisci una nota per riga: nella pagina pubblica verranno mostrate come elenco puntato.')
                    ->rows(5)
                    ->columnSpanFull(),
                RichEditor::make('ideal_for')
                    ->label('Ideale per')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Immagine')
                    ->image()
                    ->previewable(false)
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
