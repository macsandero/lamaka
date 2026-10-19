<?php

namespace App\Filament\Resources\BookingSubmissions\Schemas;

use App\Models\BookingSubmission;
use App\Models\Experience;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BookingSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gestione')
                    ->schema([
                        Select::make('status')
                            ->label('Stato')
                            ->options(BookingSubmission::STATUSES)
                            ->live()
                            ->required(),
                        Select::make('experience_id')
                            ->label('Esperienza')
                            ->placeholder('Altro / prenotazione generica')
                            ->options(fn (): array => Experience::query()->ordered()->pluck('title', 'id')->all()),
                        DatePicker::make('booking_date')
                            ->label('Giorno')
                            ->required(fn (Get $get): bool => $get('status') === 'confirmed'),
                        TextInput::make('animals')
                            ->label('Numero animali')
                            ->helperText('Necessario per confermare e calcolare la disponibilità.')
                            ->integer()
                            ->minValue(fn (Get $get): int => $get('status') === 'confirmed' ? 1 : 0)
                            ->maxValue(BookingSubmission::MAX_DAILY_ANIMALS)
                            ->required(fn (Get $get): bool => $get('status') === 'confirmed'),
                        Select::make('cancellation_reason')
                            ->label('Motivo annullamento')
                            ->options(array_combine(BookingSubmission::CANCELLATION_REASONS, BookingSubmission::CANCELLATION_REASONS))
                            ->live()
                            ->visible(fn (Get $get): bool => $get('status') === 'cancelled')
                            ->required(fn (Get $get): bool => $get('status') === 'cancelled'),
                        Textarea::make('cancellation_reason_other')
                            ->label('Specifica il motivo')
                            ->maxLength(500)
                            ->visible(fn (Get $get): bool => $get('status') === 'cancelled' && $get('cancellation_reason') === 'Altro')
                            ->required(fn (Get $get): bool => $get('status') === 'cancelled' && $get('cancellation_reason') === 'Altro'),
                        Textarea::make('notes')
                            ->label('Note interne')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
