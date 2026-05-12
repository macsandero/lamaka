<?php

namespace App\Filament\Resources\BookingSubmissions\Pages;

use App\Filament\Resources\BookingSubmissions\BookingSubmissionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingSubmission extends ViewRecord
{
    protected static string $resource = BookingSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
