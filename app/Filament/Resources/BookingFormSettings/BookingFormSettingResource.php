<?php

namespace App\Filament\Resources\BookingFormSettings;

use App\Filament\Resources\BookingFormSettings\Pages\ManageBookingFormSettings;
use App\Models\BookingFormSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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

    protected static ?string $navigationLabel = 'Testi modulo';

    protected static ?string $modelLabel = 'testi modulo';

    protected static ?string $pluralModelLabel = 'testi modulo';

    protected static ?string $recordTitleAttribute = 'heading';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
