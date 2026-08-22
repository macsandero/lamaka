<?php

namespace App\Filament\Resources\ContactSettings;

use App\Filament\Forms\EnglishContent;
use App\Filament\Resources\ContactSettings\Pages\ManageContactSettings;
use App\Models\ContactSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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

    protected static ?string $navigationLabel = 'Footer e contatti';

    protected static ?string $modelLabel = 'contatto';

    protected static ?string $pluralModelLabel = 'footer e contatti';

    protected static ?string $recordTitleAttribute = 'business_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contatti')
                    ->schema([
                        TextInput::make('business_name')
                            ->label('Nome attivita')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('footer_logo')
                            ->label('Logo footer')
                            ->image()
                            ->previewable(false)
                            ->disk('public')
                            ->directory('footer')
                            ->columnSpanFull(),
                        TextInput::make('heading')
                            ->label('Titolo sezione')
                            ->maxLength(255),
                        Textarea::make('body')
                            ->label('Testo contatti')
                            ->rows(4)
                            ->columnSpanFull(),
                        RichEditor::make('footer_body')
                            ->label('Testo footer')
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
                        TextInput::make('map_query')
                            ->label('Ricerca Google Maps')
                            ->helperText('Inserisci indirizzo, luogo o coordinate da mostrare nella mappa.')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255),
                        RichEditor::make('instagram_note')
                            ->label('Testo sotto Instagram')
                            ->columnSpanFull(),
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('booking_label')
                            ->label('Testo pulsante prenotazione')
                            ->maxLength(255),
                        TextInput::make('booking_url')
                            ->label('Link prenotazione')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Mostra footer')
                            ->default(true),
                    ])
                    ->columns(2),
                Section::make('Footer')
                    ->schema([
                        RichEditor::make('footer_note')
                            ->label('Nota footer')
                            ->columnSpanFull(),
                        TextInput::make('directions_label')
                            ->label('Testo link indicazioni')
                            ->maxLength(255),
                        TextInput::make('directions_url')
                            ->label('Link indicazioni')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('privacy_url')
                            ->label('Link Privacy policy')
                            ->helperText('Se vuoto, il footer userà la pagina Privacy Policy gestita in admin.')
                            ->maxLength(255),
                        TextInput::make('cookie_url')
                            ->label('Link Cookie policy')
                            ->helperText('Se vuoto, il footer userà la pagina Cookie Policy gestita in admin.')
                            ->maxLength(255),
                        TextInput::make('legal_text')
                            ->label('Copyright')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('company_name')
                            ->label('Ragione sociale')
                            ->maxLength(255),
                        TextInput::make('tax_code')
                            ->label('Codice fiscale')
                            ->maxLength(255),
                        TextInput::make('vat_number')
                            ->label('Partita IVA')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                EnglishContent::section([
                    ['heading', 'Titolo contatti'], ['body', 'Testo contatti', 'textarea'],
                    ['footer_body', 'Testo footer', 'rich'], ['address', 'Indirizzo', 'textarea'],
                    ['instagram_note', 'Testo Instagram', 'rich'], ['booking_label', 'Pulsante prenotazione'],
                    ['footer_note', 'Nota footer', 'rich'], ['directions_label', 'Link indicazioni'],
                    ['legal_text', 'Copyright'],
                ]),
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
