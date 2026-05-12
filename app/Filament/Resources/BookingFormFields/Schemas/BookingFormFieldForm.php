<?php

namespace App\Filament\Resources\BookingFormFields\Schemas;

use App\Models\BookingFormField;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingFormFieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Campo')
                    ->schema([
                        TextInput::make('label')
                            ->label('Etichetta')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('key')
                            ->label('Chiave tecnica')
                            ->helperText('Lascia vuoto per generarla dall’etichetta. Usa solo lettere, numeri e underscore.')
                            ->maxLength(255)
                            ->regex('/^[a-z0-9_]+$/')
                            ->unique(ignoreRecord: true),
                        Select::make('type')
                            ->label('Tipo')
                            ->options(BookingFormField::TYPES)
                            ->default('text')
                            ->required(),
                        TextInput::make('placeholder')
                            ->label('Placeholder')
                            ->maxLength(255),
                        Textarea::make('help_text')
                            ->label('Aiuto')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('options')
                            ->label('Opzioni')
                            ->helperText('Solo per il tipo Selezione: una opzione per riga.')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Ordine')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_required')
                            ->label('Obbligatorio')
                            ->default(false),
                        Toggle::make('is_full_width')
                            ->label('Larghezza intera')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Visibile')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
