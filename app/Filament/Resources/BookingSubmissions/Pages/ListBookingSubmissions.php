<?php

namespace App\Filament\Resources\BookingSubmissions\Pages;

use App\Filament\Resources\BookingSubmissions\BookingSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingSubmissions extends ListRecords
{
    protected static string $resource = BookingSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
