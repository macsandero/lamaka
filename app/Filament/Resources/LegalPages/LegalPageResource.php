<?php

namespace App\Filament\Resources\LegalPages;

use App\Filament\Forms\EnglishContent;
use App\Filament\Resources\LegalPages\Pages\EditLegalPage;
use App\Filament\Resources\LegalPages\Pages\ListLegalPages;
use App\Models\LegalPage;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
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

class LegalPageResource extends Resource
{
    protected static ?string $model = LegalPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Contenuti';

    protected static ?string $navigationLabel = 'Pagine legali';

    protected static ?string $modelLabel = 'pagina legale';

    protected static ?string $pluralModelLabel = 'pagine legali';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contenuto')
                    ->schema([
                        TextInput::make('slug')
                            ->label('URL')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('title')
                            ->label('Titolo')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('body')
                            ->label('Testo')
                            ->required()
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Pubblicata')
                            ->default(true),
                    ])
                    ->columns(2),
                EnglishContent::section([
                    ['title', 'Titolo'], ['body', 'Testo', 'rich'],
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('URL'),
                IconColumn::make('is_active')
                    ->label('Pubblicata')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLegalPages::route('/'),
            'edit' => EditLegalPage::route('/{record}/edit'),
        ];
    }
}
