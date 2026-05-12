<?php

namespace App\Filament\Resources\BookingSubmissions\Schemas;

use App\Models\BookingSubmission;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
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
                            ->required(),
                        Textarea::make('notes')
                            ->label('Note interne')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
