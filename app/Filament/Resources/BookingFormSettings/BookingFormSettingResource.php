<?php

namespace App\Filament\Resources\BookingFormSettings;

use App\Filament\Forms\EnglishContent;
use App\Filament\Resources\BookingFormSettings\Pages\ManageBookingFormSettings;
use App\Models\BookingFormSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class BookingFormSettingResource extends Resource
{
    protected static ?string $model = BookingFormSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Prenotazioni';

    protected static ?string $navigationLabel = 'Impostazioni prenotazioni';

    protected static ?string $modelLabel = 'testi modulo';

    protected static ?string $pluralModelLabel = 'testi modulo';

    protected static ?string $recordTitleAttribute = 'heading';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sospensione prenotazioni')
                    ->description('Sospende tutte le nuove richieste dal sito, con precedenza sui giorni disponibili delle esperienze.')
                    ->schema([
                        Toggle::make('bookings_suspended')
                            ->label('Sospendi le prenotazioni')
                            ->helperText('Disattiva l’interruttore per riaprire subito le prenotazioni.')
                            ->default(false)
                            ->live(),
                        DatePicker::make('booking_suspension_until')
                            ->label('Sospendi fino al')
                            ->helperText('Il giorno selezionato è incluso. Le prenotazioni riaprono automaticamente il giorno successivo, secondo l’ora italiana.')
                            ->visible(fn (Get $get): bool => (bool) $get('bookings_suspended'))
                            ->required(fn (Get $get): bool => (bool) $get('bookings_suspended')),
                        Textarea::make('booking_suspension_message')
                            ->label('Avviso per i clienti')
                            ->helperText('Compare al clic su “Prenota questa esperienza” e al posto del modulo. Usa {data_scadenza} per mostrare la data impostata, ad esempio: Prenotazioni sospese fino al {data_scadenza} incluso.')
                            ->rows(4)
                            ->maxLength(2000)
                            ->visible(fn (Get $get): bool => (bool) $get('bookings_suspended'))
                            ->required(fn (Get $get): bool => (bool) $get('bookings_suspended'))
                            ->columnSpanFull(),
                        Textarea::make('translations.en.booking_suspension_message')
                            ->label('Avviso per i clienti (EN)')
                            ->helperText('Facoltativo. Se vuoto, viene mostrato il testo italiano. Anche qui puoi usare {data_scadenza}, nel formato giorno/mese/anno.')
                            ->rows(4)
                            ->maxLength(2000)
                            ->visible(fn (Get $get): bool => (bool) $get('bookings_suspended'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Sezione prenota')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Sopratitolo')
                            ->maxLength(255),
                        TextInput::make('heading')
                            ->label('Titolo')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body')
                            ->label('Testo introduttivo')
                            ->rows(4)
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Immagine sotto al testo')
                            ->image()
                            ->disk('public')
                            ->directory('booking')
                            ->columnSpanFull(),
                        TextInput::make('submit_label')
                            ->label('Testo pulsante')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('success_message')
                            ->label('Messaggio dopo invio')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Modulo attivo')
                            ->default(true),
                    ])
                    ->columns(2),
                EnglishContent::section([
                    ['eyebrow', 'Sopratitolo'], ['heading', 'Titolo'],
                    ['body', 'Testo introduttivo', 'textarea'], ['submit_label', 'Testo pulsante'],
                    ['success_message', 'Messaggio dopo invio', 'textarea'],
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('heading')
            ->columns([
                TextColumn::make('heading')
                    ->label('Titolo')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
                IconColumn::make('bookings_suspended')
                    ->label('Prenotazioni sospese')
                    ->state(fn (BookingFormSetting $record): bool => $record->bookingsAreSuspended())
                    ->boolean(),
                TextColumn::make('booking_suspension_until')
                    ->label('Scadenza sospensione')
                    ->date('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBookingFormSettings::route('/'),
        ];
    }
}
