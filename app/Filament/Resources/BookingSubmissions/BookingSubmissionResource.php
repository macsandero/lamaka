<?php

namespace App\Filament\Resources\BookingSubmissions;

use App\Filament\Resources\BookingSubmissions\Pages\EditBookingSubmission;
use App\Filament\Resources\BookingSubmissions\Pages\ListBookingSubmissions;
use App\Filament\Resources\BookingSubmissions\Pages\ViewBookingSubmission;
use App\Filament\Resources\BookingSubmissions\Schemas\BookingSubmissionForm;
use App\Filament\Resources\BookingSubmissions\Schemas\BookingSubmissionInfolist;
use App\Filament\Resources\BookingSubmissions\Tables\BookingSubmissionsTable;
use App\Models\BookingSubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BookingSubmissionResource extends Resource
{
    protected static ?string $model = BookingSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected static string|UnitEnum|null $navigationGroup = 'Prenotazioni';

    protected static ?string $navigationLabel = 'Richieste';

    protected static ?string $modelLabel = 'richiesta';

    protected static ?string $pluralModelLabel = 'richieste';

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return BookingSubmissionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BookingSubmissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingSubmissionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingSubmissions::route('/'),
            'view' => ViewBookingSubmission::route('/{record}'),
            'edit' => EditBookingSubmission::route('/{record}/edit'),
        ];
    }
}
