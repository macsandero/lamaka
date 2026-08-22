<?php

namespace App\Filament\Resources\AboutContents;

use App\Filament\Forms\EnglishContent;
use App\Filament\Resources\AboutContents\Pages\ManageAboutContents;
use App\Models\HomepageContent;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AboutContentResource extends Resource
{
    protected static ?string $model = HomepageContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?string $navigationLabel = 'Chi siamo';

    protected static ?string $modelLabel = 'contenuto chi siamo';

    protected static ?string $pluralModelLabel = 'chi siamo';

    protected static ?string $recordTitleAttribute = 'about_title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Chi siamo')
                    ->schema([
                        TextInput::make('about_eyebrow')
                            ->label('Sopratitolo')
                            ->maxLength(255),
                        TextInput::make('about_title')
                            ->label('Titolo')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('about_body')
                            ->label('Testo')
                            ->columnSpanFull(),
                        FileUpload::make('about_image')
                            ->label('Immagine')
                            ->image()
                            ->disk('public')
                            ->directory('homepage')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                EnglishContent::section([
                    ['about_eyebrow', 'Sopratitolo'], ['about_title', 'Titolo'],
                    ['about_body', 'Testo', 'rich'],
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('about_title')
            ->columns([
                TextColumn::make('about_title')
                    ->label('Titolo')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAboutContents::route('/'),
        ];
    }
}
