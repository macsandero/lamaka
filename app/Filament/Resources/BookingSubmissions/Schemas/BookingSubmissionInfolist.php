<?php

namespace App\Filament\Resources\BookingSubmissions\Schemas;

use App\Models\BookingSubmission;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingSubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Richiesta')
                    ->schema([
                        TextEntry::make('reference')
                            ->label('Riferimento'),
                        TextEntry::make('status')
                            ->label('Stato')
                            ->formatStateUsing(fn (string $state): string => BookingSubmission::STATUSES[$state] ?? $state)
                            ->badge(),
                        TextEntry::make('created_at')
                            ->label('Ricevuta')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(3),
                Section::make('Dati inviati')
                    ->schema([
                        KeyValueEntry::make('data')
                            ->label('')
                            ->state(fn (BookingSubmission $record): array => $record->flatData())
                            ->columnSpanFull(),
                    ]),
                Section::make('Note interne')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('')
                            ->placeholder('Nessuna nota')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
