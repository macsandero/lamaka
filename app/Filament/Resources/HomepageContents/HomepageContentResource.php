<?php

namespace App\Filament\Resources\HomepageContents;

use App\Filament\Resources\HomepageContents\Pages\ManageHomepageContents;
use App\Models\HomepageContent;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomepageContentResource extends Resource
{
    protected static ?string $model = HomepageContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Homepage';

    protected static ?string $modelLabel = 'contenuto homepage';

    protected static ?string $pluralModelLabel = 'homepage';

    protected static ?string $recordTitleAttribute = 'hero_title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero')
                    ->schema([
                        TextInput::make('hero_eyebrow')
                            ->label('Sopratitolo')
                            ->maxLength(255),
                        Textarea::make('hero_title')
                            ->label('Titolo')
                            ->required()
                            ->rows(3)
                            ->maxLength(255)
                            ->columnSpanFull(),
                        RichEditor::make('hero_subtitle')
                            ->label('Testo')
                            ->columnSpanFull(),
                        TextInput::make('hero_button_label')
                            ->label('Testo pulsante')
                            ->maxLength(255),
                        TextInput::make('hero_button_anchor')
                            ->label('Link pulsante')
                            ->maxLength(255),
                        FileUpload::make('hero_video')
                            ->label('Video hero')
                            ->disk('public')
                            ->directory('homepage')
                            ->acceptedFileTypes(['video/mp4'])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Esperienze')
                    ->schema([
                        TextInput::make('experiences_eyebrow')
                            ->label('Sopratitolo')
                            ->maxLength(255),
                        TextInput::make('experiences_title')
                            ->label('Titolo')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('Animali')
                    ->schema([
                        TextInput::make('animals_eyebrow')
                            ->label('Sopratitolo')
                            ->maxLength(255),
                        TextInput::make('animals_title')
                            ->label('Titolo')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('hero_title')
            ->columns([
                TextColumn::make('hero_title')
                    ->searchable(),
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
            'index' => ManageHomepageContents::route('/'),
        ];
    }
}
