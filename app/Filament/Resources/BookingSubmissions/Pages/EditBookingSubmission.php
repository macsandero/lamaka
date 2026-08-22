<?php

namespace App\Filament\Resources\BookingSubmissions\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\BookingSubmissions\BookingSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingSubmission extends EditRecord
{
    use HasSaveConfirmation;

    protected static string $resource = BookingSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
