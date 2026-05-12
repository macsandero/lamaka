<?php

namespace App\Filament\Resources\ContactSettings;

use App\Filament\Resources\ContactSettings\Pages\ManageContactSettings;
use App\Models\ContactSetting;
use BackedEnum;
use Filament\Actions\EditAction;
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

class ContactSettingResource extends Resource
{
    protected static ?string $model = ContactSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Contatti';

    protected static ?string $modelLabel = 'contatto';

    protected static ?string $pluralModelLabel = 'contatti';

    protected static ?string $recordTitleAttribute = 'business_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni')
                    ->schema([
                        TextInput::make('business_name')
                            ->label('Nome attivita')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('heading')
                            ->label('Titolo sezione')
                            ->maxLength(255),
                        Textarea::make('body')
                            ->label('Testo')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Telefono')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->maxLength(255),
                        Textarea::make('address')
                            ->label('Indirizzo')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('booking_label')
                            ->label('Testo pulsante prenotazione')
                            ->maxLength(255),
                        TextInput::make('booking_url')
                            ->label('Link prenotazione')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Mostra sezione contatti')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('business_name')
            ->columns([
                TextColumn::make('business_name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email'),
                IconColumn::make('is_active')
                    ->label('Visibile')
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
            'index' => ManageContactSettings::route('/'),
        ];
    }
}
